<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {

            $table->enum('admin_status', [
                'pending',
                'approved',
                'rejected',
                'completed'
            ])->default('pending');

            $table->text('admin_notes')->nullable();

            $table->timestamp('admin_processed_at')->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users');

            $table->string('design_file_path')->nullable();

            $table->decimal('design_cost', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {

            $table->dropForeign(['processed_by']);

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