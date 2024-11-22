<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('images', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('hotel_id');
        $table->unsignedBigInteger('room_id')->nullable(); // Cho phép NULL
        $table->unsignedBigInteger('amenities_id')->nullable(); // Cho phép NULL
        $table->string('image_path');
        $table->text('note')->nullable();
        $table->timestamps();

        // Đặt ràng buộc khóa ngoại
        $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        $table->foreign('amenities_id')->references('id')->on('amenities')->onDelete('cascade');
    });
}

  /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
