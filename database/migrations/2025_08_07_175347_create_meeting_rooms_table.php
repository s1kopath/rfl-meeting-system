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
        Schema::create('meeting_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Amenities
            $table->integer('seats')->default(0);
            $table->integer('tables')->default(1);
            $table->integer('ac_units')->default(1);
            $table->integer('washroom_facilities')->default(1);
            $table->boolean('has_podium')->default(false);
            $table->boolean('has_sound_system')->default(false);
            $table->boolean('has_projector')->default(false);
            $table->integer('monitors')->default(0);
            $table->integer('tv_screens')->default(0);
            $table->boolean('has_ethernet')->default(false);
            $table->boolean('has_wifi')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_rooms');
    }
}; 