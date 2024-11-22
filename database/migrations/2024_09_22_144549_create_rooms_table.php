<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('rooms', function (Blueprint $table) {
        $table->string('room_number')->after('id'); // Thêm cột room_number
        $table->string('type')->after('room_number'); // Thêm cột type
        $table->integer('capacity')->after('type'); // Thêm cột capacity
    });
}

public function down()
{
    Schema::table('rooms', function (Blueprint $table) {
        $table->dropColumn(['room_number', 'type', 'capacity']); // Xóa các cột khi rollback
    });
}

}
