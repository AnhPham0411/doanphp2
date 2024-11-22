<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomAmenityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_amenity', function (Blueprint $table) {
            $table->id(); // ID của bản ghi
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade'); // Khóa ngoại liên kết với bảng rooms
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade'); // Khóa ngoại liên kết với bảng amenities
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_amenity'); // Xóa bảng khi rollback
    }
}
