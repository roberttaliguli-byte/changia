<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {

            if (!Schema::hasColumn('cards', 'admin_status')) {
                $table->enum('admin_status', [
                    'pending',
                    'approved',
                    'rejected',
                    'completed'
                ])->default('pending');
            }

            if (!Schema::hasColumn('cards', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }

            if (!Schema::hasColumn('cards', 'admin_processed_at')) {
                $table->timestamp('admin_processed_at')->nullable();
            }

            if (!Schema::hasColumn('cards', 'processed_by')) {
                $table->foreignId('processed_by')
                    ->nullable()
                    ->constrained('users');
            }

            if (!Schema::hasColumn('cards', 'design_file_path')) {
                $table->string('design_file_path')->nullable();
            }

            if (!Schema::hasColumn('cards', 'design_cost')) {
                $table->decimal('design_cost', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {

            if (Schema::hasColumn('cards', 'processed_by')) {
                $table->dropForeign(['processed_by']);
            }

            $table->dropColumn([
                'admin_status',
                'admin_notes',
                'admin_processed_at',
                'processed_by',
                'design_file_path',
                'design_cost'
            ]);
        });
    }
};