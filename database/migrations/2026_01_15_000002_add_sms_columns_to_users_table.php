<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('sms_quota')->default(500);
            $table->integer('sms_used_this_month')->default(0);
            $table->decimal('sms_balance', 10, 2)->default(0);
            $table->timestamp('sms_quota_reset_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sms_quota', 'sms_used_this_month', 'sms_balance', 'sms_quota_reset_at']);
        });
    }
};