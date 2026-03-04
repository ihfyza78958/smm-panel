<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /** Providers we support and their user-facing labels */
    private const PROVIDERS = ['google', 'github', 'facebook'];

    public function redirect(string $provider = 'google')
    {
        if (!$this->isEnabled($provider)) {
            return redirect()->route('login')
                ->with('error', ucfirst($provider) . ' login is currently disabled.');
        }

        $this->applyConfig($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider = 'google')
    {
        if (!$this->isEnabled($provider)) {
            return redirect()->route('login')
                ->with('error', ucfirst($provider) . ' login is currently disabled.');
        }

        $this->applyConfig($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Social login error [{$provider}]: " . $e->getMessage());
            return redirect()->route('login')
                ->with('error', ucfirst($provider) . ' login failed. Please try again.');
        }

        $idColumn  = "{$provider}_id";
        $socialId  = $socialUser->getId();
        $email     = $socialUser->getEmail();

        // 1. Find by provider ID
        $user = User::where($idColumn, $socialId)->first();

        if (!$user && $email) {
            // 2. Match existing account by email
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->update([$idColumn => $socialId]);
            }
        }

        if (!$user) {
            // 3. Create new account
            $user = User::create([
                'name'              => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email'             => $email,
                $idColumn           => $socialId,
                'password'          => null,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function isEnabled(string $provider): bool
    {
        return in_array($provider, self::PROVIDERS)
            && Setting::get("{$provider}_login_enabled", '0') === '1';
    }

    /**
     * Override the Socialite config at runtime with DB credentials so the
     * admin can change them without touching .env.
     */
    private function applyConfig(string $provider): void
    {
        $clientId     = Setting::get("{$provider}_client_id", '');
        $clientSecret = Setting::get("{$provider}_client_secret", '');
        $redirect     = route('auth.social.callback', ['provider' => $provider]);

        config([
            "services.{$provider}.client_id"     => $clientId     ?: config("services.{$provider}.client_id"),
            "services.{$provider}.client_secret" => $clientSecret ?: config("services.{$provider}.client_secret"),
            "services.{$provider}.redirect"      => $redirect,
        ]);
    }
}
