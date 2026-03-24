<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToContributionsAndContributorsTables extends Migration
{
    public function up()
    {
        // ========================
        // Contributions Table
        // ========================
        Schema::table('contributions', function (Blueprint $table) {

            if (!Schema::hasColumn('contributions', 'rejected_by')) {
                $table->foreignId('rejected_by')
                    ->nullable()
                    ->after('approved_by')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('contributions', 'approved_at')) {
                $table->timestamp('approved_at')
                    ->nullable()
                    ->after('approved_by');
            }

            if (!Schema::hasColumn('contributions', 'rejected_at')) {
                $table->timestamp('rejected_at')
                    ->nullable()
                    ->after('rejected_by');
            }

            if (!Schema::hasColumn('contributions', 'notes')) {
                $table->text('notes')
                    ->nullable()
                    ->after('proof');
            }

            if (!Schema::hasColumn('contributions', 'reference_number')) {
                $table->string('reference_number')
                    ->unique()
                    ->nullable()
                    ->after('id');
            }
        });

        // ========================
        // Contributors Table
        // ========================
Schema::table('contributors', function (Blueprint $table) {

    if (!Schema::hasColumn('contributors', 'registration_method')) {
        $table->string('registration_method')
            ->default('manual');
    }

    if (!Schema::hasColumn('contributors', 'registered_at')) {
        $table->timestamp('registered_at')
            ->nullable();
    }

    if (!Schema::hasColumn('contributors', 'notes')) {
        $table->text('notes')
            ->nullable();
    }
});
    }

    public function down()
    {
        // ========================
        // Contributions Table
        // ========================
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']); // important!
            $table->dropColumn([
                'rejected_by',
                'approved_at',
                'rejected_at',
                'notes',
                'reference_number'
            ]);
        });

        // ========================
        // Contributors Table
        // ========================
        Schema::table('contributors', function (Blueprint $table) {
            $table->dropColumn([
                'registration_method',
                'registered_at',
                'notes'
            ]);
        });
    }
}