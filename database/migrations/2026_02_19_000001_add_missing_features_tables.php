<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Settings table (key-value store) ──
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general, payment, seo, mail, etc.
            $table->timestamps();
        });

        // ── Referral / Affiliate system ──
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('referral_code')->unique();
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('signups')->default(0);
            $table->decimal('total_funds_referred', 15, 4)->default(0);
            $table->decimal('earned_commission', 15, 4)->default(0);
            $table->decimal('requested_commission', 15, 4)->default(0);
            $table->decimal('total_commission', 15, 4)->default(0);
            $table->string('status')->default('active'); // active, suspended
            $table->timestamps();
        });

        Schema::create('referral_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('referral_code');
            $table->decimal('amount', 15, 4);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        // ── Coupons ──
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 15, 4);
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 4);
            $table->timestamps();

            $table->unique(['coupon_id', 'user_id']); // Each user can only use a coupon once
        });

        // ── Payment Methods (dynamic config — like smmnepal's paymentmethods table) ──
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('display_name');
            $table->decimal('min_amount', 15, 4)->default(10);
            $table->decimal('max_amount', 15, 4)->default(100000);
            $table->decimal('fee_percentage', 5, 2)->default(0);
            $table->decimal('bonus_percentage', 5, 2)->default(0);
            $table->decimal('bonus_start_amount', 15, 4)->default(0);
            $table->string('currency')->default('NPR');
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable(); // Gateway-specific config
            $table->text('instructions')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ── News / Announcements ──
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Pages CMS ──
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        // ── FAQ ──
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Activity / Audit Log ──
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        // ── Additional user fields ──
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('spent', 15, 4)->default(0)->after('balance');
            $table->string('ref_code')->nullable()->unique()->after('api_key');
            $table->string('ref_by')->nullable()->after('ref_code');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('ref_by');
            $table->string('currency')->default('NPR')->after('timezone');
            $table->string('phone')->nullable()->after('email');
        });

        // ── Additional service fields ──
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('refill_available');
            $table->unsignedInteger('refill_days')->default(0)->after('sort_order');
            $table->decimal('profit_margin', 5, 2)->default(20)->after('refill_days');
            $table->boolean('cancel_allowed')->default(false)->after('profit_margin');
        });

        // ── Additional order fields ──
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_drip_feed')->default(false)->after('provider_order_id');
            $table->unsignedInteger('drip_feed_runs')->nullable()->after('is_drip_feed');
            $table->unsignedInteger('drip_feed_interval')->nullable()->after('drip_feed_runs');
            $table->string('order_source')->default('web')->after('completed_at'); // web, api
            $table->decimal('profit', 15, 4)->default(0)->after('order_source');
        });

        // ── Additional category fields ──
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_drip_feed', 'drip_feed_runs', 'drip_feed_interval', 'order_source', 'profit']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['sort_order', 'refill_days', 'profit_margin', 'cancel_allowed']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['spent', 'ref_code', 'ref_by', 'discount_percentage', 'currency', 'phone']);
        });

        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('referral_payouts');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('settings');
    }
};
