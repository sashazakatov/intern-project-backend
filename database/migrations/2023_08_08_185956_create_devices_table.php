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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('administrator_id');
            $table->string('name')->require();
            $table->string('device_type');
            $table->boolean('phase_active');
            $table->string('phase_type');
            $table->float('sum_power');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->text('location');
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->timestamps();
            
            $table->foreign('owner_id')->references('id')->on('users'); // Пример внешнего ключа на пользователя
            $table->foreign('administrator_id')->references('id')->on('users'); // Пример внешнего ключа на пользователя
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
