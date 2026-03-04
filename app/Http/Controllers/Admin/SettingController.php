<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Flatten all setting groups into one key→value array so the view can
        // access settings directly as $settings['site_name'] etc.
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        // All editable setting keys with their validation rules and group.
        $allSettings = [
            // general
            'site_name'             => ['required|string|max:255',     'general'],
            'site_description'      => ['nullable|string|max:500',     'general'],
            'currency_symbol'       => ['nullable|string|max:5',       'general'],
            'support_email'         => ['nullable|email',              'general'],
            'meta_description'      => ['nullable|string|max:500',     'general'],
            'maintenance_mode'      => ['nullable',                    'general'],
            'global_profit_margin'  => ['nullable|numeric|min:0',      'general'],
            // payment
            'min_deposit'           => ['nullable|numeric|min:0',      'payment'],
            'max_deposit'           => ['nullable|numeric|min:0',      'payment'],
            'esewa_enabled'         => ['nullable',                    'payment'],
            'khalti_enabled'        => ['nullable',                    'payment'],
            'manual_topup_enabled'  => ['nullable',                    'payment'],
            // referral
            'referral_enabled'      => ['nullable',                    'referral'],
            'referral_percentage'   => ['nullable|numeric|min:0|max:100', 'referral'],
            'referral_min_payout'   => ['nullable|numeric|min:0',      'referral'],
            // social login
            'google_login_enabled'   => ['nullable',          'social'],
            'google_client_id'       => ['nullable|string',   'social'],
            'google_client_secret'   => ['nullable|string',   'social'],
            'github_login_enabled'   => ['nullable',          'social'],
            'github_client_id'       => ['nullable|string',   'social'],
            'github_client_secret'   => ['nullable|string',   'social'],
            'facebook_login_enabled' => ['nullable',          'social'],
            'facebook_client_id'     => ['nullable|string',   'social'],
            'facebook_client_secret' => ['nullable|string',   'social'],
        ];

        $rules = array_map(fn($v) => $v[0], $allSettings);
        $request->validate($rules);

        foreach ($allSettings as $key => [$rule, $group]) {
            // Checkboxes are absent when unchecked — treat missing as '0'
            $value = $request->has($key) ? $request->input($key) : '0';
            Setting::set($key, $value, $group);
        }

        ActivityLog::log('settings_updated', 'Site settings updated');

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    /**
     * Announcements management.
     */
    public function announcements()
    {
        $announcements = \App\Models\Announcement::latest()->paginate(20);
        return view('admin.settings.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        \App\Models\Announcement::create($request->only('title', 'content', 'icon', 'is_active'));

        return back()->with('success', 'Announcement created.');
    }

    public function deleteAnnouncement(\App\Models\Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
