<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('room_amenity', function (Blueprint $table) {
        $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
        $table->primary(['room_id', 'amenity_id']);
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_amenities');
    }
}
