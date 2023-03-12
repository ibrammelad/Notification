<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->double('longFrom');
            $table->double('latFrom');
            $table->double('longTo');
            $table->double('latTo');
            $table->double('cost');
            $table->boolean('status');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('captain_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->foreign('captain_id')->on('captains')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
