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
        $settings = [
            'general' => Setting::getGroup('general'),
            'seo' => Setting::getGroup('seo'),
            'mail' => Setting::getGroup('mail'),
            'payment' => Setting::getGroup('payment'),
            'referral' => Setting::getGroup('referral'),
            'modules' => Setting::getGroup('modules'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $group = $request->input('group', 'general');

        $settingsMap = [
            'general' => [
                'site_name' => 'required|string|max:255',
                'site_description' => 'nullable|string|max:500',
                'site_url' => 'nullable|url',
                'currency' => 'nullable|string|max:5',
                'maintenance_mode' => 'nullable|boolean',
                'registration_enabled' => 'nullable|boolean',
                'free_balance' => 'nullable|numeric|min:0',
                'email_confirmation' => 'nullable|boolean',
            ],
            'seo' => [
                'seo_title' => 'nullable|string|max:255',
                'seo_keywords' => 'nullable|string',
                'seo_description' => 'nullable|string|max:500',
            ],
            'mail' => [
                'smtp_host' => 'nullable|string',
                'smtp_port' => 'nullable|integer',
                'smtp_user' => 'nullable|string',
                'smtp_password' => 'nullable|string',
                'smtp_encryption' => 'nullable|in:ssl,tls,null',
                'mail_from_name' => 'nullable|string',
                'mail_from_address' => 'nullable|email',
            ],
            'payment' => [
                'esewa_enabled' => 'nullable|boolean',
                'khalti_enabled' => 'nullable|boolean',
                'manual_payment_enabled' => 'nullable|boolean',
                'min_deposit' => 'nullable|numeric|min:0',
                'max_deposit' => 'nullable|numeric|min:0',
            ],
            'referral' => [
                'referral_enabled' => 'nullable|boolean',
                'referral_commission_percent' => 'nullable|numeric|min:0|max:100',
                'referral_min_payout' => 'nullable|numeric|min:0',
            ],
            'modules' => [
                'ticket_system' => 'nullable|boolean',
                'mass_order' => 'nullable|boolean',
                'coupon_system' => 'nullable|boolean',
                'blog_enabled' => 'nullable|boolean',
                'faq_enabled' => 'nullable|boolean',
                'api_enabled' => 'nullable|boolean',
            ],
        ];

        $rules = $settingsMap[$group] ?? [];
        if (!empty($rules)) {
            $request->validate($rules);
        }

        // Save all submitted settings for this group
        foreach (array_keys($rules) as $key) {
            $value = $request->input($key);
            if ($value !== null) {
                Setting::set($key, $value, $group);
            }
        }

        ActivityLog::log('settings_updated', "Settings group '{$group}' updated");

        return redirect()->back()->with('success', ucfirst($group) . ' settings saved successfully.');
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
