<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToContributorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing columns to contributors table
        Schema::table('contributors', function (Blueprint $table) {
            // Check if status column doesn't exist
            if (!Schema::hasColumn('contributors', 'status')) {
                $table->string('status')->default('pending')->after('remaining_amount');
            }
            
            // Check if registration_method column doesn't exist
            if (!Schema::hasColumn('contributors', 'registration_method')) {
                $table->string('registration_method')->default('manual')->after('status');
            }
            
            // Check if registered_at column doesn't exist
            if (!Schema::hasColumn('contributors', 'registered_at')) {
                $table->timestamp('registered_at')->nullable()->after('registration_method');
            }
            
            // Check if notes column doesn't exist
            if (!Schema::hasColumn('contributors', 'notes')) {
                $table->text('notes')->nullable()->after('registered_at');
            }
        });
        
        // Add missing columns to contributions table
        Schema::table('contributions', function (Blueprint $table) {
            // Check if rejected_by column doesn't exist
            if (!Schema::hasColumn('contributions', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('approved_by')
                    ->constrained('users')->nullOnDelete();
            }
            
            // Check if approved_at column doesn't exist
            if (!Schema::hasColumn('contributions', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            
            // Check if rejected_at column doesn't exist
            if (!Schema::hasColumn('contributions', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            
            // Check if notes column doesn't exist
            if (!Schema::hasColumn('contributions', 'notes')) {
                $table->text('notes')->nullable()->after('proof');
            }
            
            // Check if reference_number column doesn't exist
            if (!Schema::hasColumn('contributions', 'reference_number')) {
                $table->string('reference_number')->unique()->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributors', function (Blueprint $table) {
            $table->dropColumn(['status', 'registration_method', 'registered_at', 'notes']);
        });
        
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn(['rejected_by', 'approved_at', 'rejected_at', 'notes', 'reference_number']);
        });
    }
}