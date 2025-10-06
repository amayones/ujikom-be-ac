<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add indexes for performance
        Schema::table('films', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['film_id', 'date']);
            $table->index('date');
            $table->unique(['film_id', 'studio_id', 'date', 'time']);
        });

        Schema::table('schedule_seats', function (Blueprint $table) {
            $table->index(['schedule_id', 'status']);
            $table->unique(['schedule_id', 'studio_seat_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index('order_date');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['film_id', 'date']);
            $table->dropIndex(['date']);
            $table->dropUnique(['film_id', 'studio_id', 'date', 'time']);
        });

        Schema::table('schedule_seats', function (Blueprint $table) {
            $table->dropIndex(['schedule_id', 'status']);
            $table->dropUnique(['schedule_id', 'studio_seat_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['order_date']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};