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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('genre');
            $table->integer('duration');
            $table->text('description');
            $table->enum('status', ['now_playing', 'coming_soon', 'ended'])->default('coming_soon');
            $table->string('poster')->nullable();
            $table->string('director')->nullable();
            $table->date('release_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
