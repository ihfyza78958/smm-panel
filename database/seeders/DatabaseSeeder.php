<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\SmmProvider;
use App\Models\Setting;
use App\Models\Announcement;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users (idempotent - safe to re-run)
        $admin = User::firstOrCreate(
            ['email' => 'admin@smmpanel.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'balance' => 100000,
                'ref_code' => Str::random(8),
            ]
        );

        $testUser = User::firstOrCreate(
            ['email' => 'user@smmpanel.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'role' => 'user',
                'balance' => 500.00,
                'ref_code' => Str::random(8),
            ]
        );

        // 2. Create Providers
        $jap = SmmProvider::firstOrCreate(
            ['domain' => 'justanotherpanel.com'],
            [
                'url' => 'https://justanotherpanel.com/api/v2',
                'api_key' => 'test_key_123',
                'balance' => 50.00,
                'currency' => 'USD'
            ]
        );

        // 3. Create Categories & Services (idempotent)
        $catInsta = Category::firstOrCreate(['slug' => 'instagram-likes'], ['name' => 'Instagram Likes', 'sort_order' => 1, 'icon' => '❤️']);
        $catYt = Category::firstOrCreate(['slug' => 'youtube-views'], ['name' => 'YouTube Views', 'sort_order' => 2, 'icon' => '▶️']);
        $catTiktok = Category::firstOrCreate(['slug' => 'tiktok'], ['name' => 'TikTok', 'sort_order' => 3, 'icon' => '🎵']);
        $catFb = Category::firstOrCreate(['slug' => 'facebook'], ['name' => 'Facebook', 'sort_order' => 4, 'icon' => '👍']);

        Service::firstOrCreate(
            ['name' => 'Instagram Likes [Instant] [Max 10K]'],
            [
                'category_id' => $catInsta->id,
                'type' => 'default',
                'price' => 50.00,
                'min_quantity' => 100,
                'max_quantity' => 10000,
                'smm_provider_id' => $jap->id,
                'provider_service_id' => '102',
                'provider_rate' => 0.10,
                'profit_margin' => 20,
                'refill_days' => 30,
            ]
        );

        Service::firstOrCreate(
            ['name' => 'Instagram Likes [Real Users]'],
            [
                'category_id' => $catInsta->id,
                'type' => 'default',
                'price' => 120.00,
                'min_quantity' => 50,
                'max_quantity' => 5000,
                'profit_margin' => 25,
            ]
        );

        Service::firstOrCreate(
            ['name' => 'YouTube Views [Non-Drop]'],
            [
                'category_id' => $catYt->id,
                'type' => 'default',
                'price' => 250.00,
                'min_quantity' => 500,
                'max_quantity' => 100000,
                'profit_margin' => 20,
            ]
        );

        // 4. Create transactions
        $testUser->transactions()->create([
            'amount' => 500,
            'type' => 'deposit',
            'payment_method' => 'manual',
            'status' => 'completed',
            'description' => 'Initial Bonus'
        ]);

        // 5. Seed Settings
        $settings = [
            'site_name' => 'Nepalboost',
            'site_description' => 'Best SMM Panel in Nepal',
            'currency_symbol' => 'NPR',
            'support_email' => 'support@nepalboost.com',
            'meta_description' => 'Best SMM Panel in Nepal providing cheap services for Instagram, YouTube, TikTok and more.',
            'maintenance_mode' => '0',
            'global_profit_margin' => '20',
            'min_deposit' => '100',
            'max_deposit' => '50000',
            'esewa_enabled' => '1',
            'khalti_enabled' => '1',
            'manual_topup_enabled' => '1',
            'referral_enabled' => '1',
            'referral_percentage' => '5',
            'referral_min_payout' => '500',
        ];
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 6. Seed Announcements (idempotent)
        Announcement::firstOrCreate(
            ['title' => 'Welcome to Nepalboost!'],
            [
                'content' => 'We are the best SMM panel in Nepal. Start placing orders now and enjoy cheap prices!',
                'icon' => 'info',
                'is_active' => true,
            ]
        );

        // 7. Seed a sample coupon (idempotent)
        Coupon::firstOrCreate(
            ['code' => 'WELCOME50'],
            [
                'amount' => 50,
                'max_uses' => 100,
                'is_active' => true,
                'expires_at' => now()->addMonths(3),
            ]
        );

        // 8. Seed pages (idempotent)
        Page::firstOrCreate(
            ['slug' => 'terms'],
            [
                'title' => 'Terms of Service',
                'content' => '<h2>Terms of Service</h2><p>By using our services, you agree to the following terms...</p>',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'privacy'],
            [
                'title' => 'Privacy Policy',
                'content' => '<h2>Privacy Policy</h2><p>We value your privacy and protect your personal data...</p>',
                'is_active' => true,
            ]
        );

        // 9. Seed FAQs (idempotent)
        Faq::firstOrCreate(
            ['question' => 'What is an SMM Panel?'],
            [
                'answer' => 'An SMM Panel is a service that allows you to buy social media services like followers, likes, views, and more at affordable prices.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'How long does delivery take?'],
            [
                'answer' => 'Most orders start within 0-30 minutes and complete within 1-24 hours depending on the service.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'What payment methods do you accept?'],
            [
                'answer' => 'We accept eSewa, Khalti, and manual bank transfer for Nepal-based payments.',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );
    }
}
