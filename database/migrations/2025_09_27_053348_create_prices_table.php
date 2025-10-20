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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['regular', 'premium', 'vip']);
            $table->decimal('price', 10, 2);
            $table->enum('day_type', ['weekday', 'weekend'])->default('weekday');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
