<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventAccountantTable extends Migration
{
    public function up()
    {
        Schema::create('event_accountant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('accountant_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['event_id', 'accountant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_accountant');
    }
}