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
Schema::create('contributions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('contributor_id')->constrained()->cascadeOnDelete();

    $table->decimal('amount', 10, 2);
    $table->string('payment_method')->nullable();

    $table->string('proof')->nullable(); // file path

    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

    $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
