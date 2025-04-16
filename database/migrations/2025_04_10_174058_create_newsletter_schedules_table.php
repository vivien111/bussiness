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
        Schema::create('newsletter_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('frequency', ['weekly', 'monthly', 'bi-monthly']);
            $table->string('day_of_week')->nullable();
            $table->integer('day_of_month')->nullable();
            $table->time('send_time');
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_schedules');
    }
};
