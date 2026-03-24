<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add event_type column if it doesn't exist
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->string('event_type')->nullable()->after('description');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('events', 'status')) {
                $table->string('status')->default('active')->after('event_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'status']);
        });
    }
};