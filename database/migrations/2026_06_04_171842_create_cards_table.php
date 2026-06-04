<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_type'); // invitation, contribution
            $table->string('title'); // harusi, send off, etc
            $table->string('groom_name')->nullable();
            $table->string('bride_name')->nullable();
            $table->string('honoree_name')->nullable(); // For send off, graduation etc
            $table->date('event_date');
            $table->time('event_time');
            $table->string('location');
            $table->text('description')->nullable();
            $table->decimal('suggested_amount', 10, 2)->nullable(); // For contribution cards
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('card_image_path')->nullable();
            $table->string('share_link')->nullable();
            $table->integer('views')->default(0);
            $table->integer('shares')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cards');
    }
};