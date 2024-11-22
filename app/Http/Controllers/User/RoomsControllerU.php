<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomsControllerU extends Controller
{
    public function showRoomsByType($type)
    {
        // Lấy danh sách phòng theo loại phòng và kèm theo hình ảnh liên kết
        $rooms = Room::with('images')->where('type', $type)->paginate(6); // Thêm phân trang cho kết quả

        // Trả về view và truyền danh sách phòng và loại phòng vào view
        return view('user.rooms_by_type', compact('rooms', 'type'));
    }
    public function showBookingForm($id)
    {
        // Tìm phòng với ID tương ứng và các quan hệ cần thiết
        $room = Room::with('hotel', 'images')->find($id); // Sử dụng find để không throw exception

        if (!$room) {
            // Nếu không tìm thấy phòng, điều hướng về trang lỗi hoặc thông báo lỗi
            return redirect()->route('rooms.index')->with('error', 'Room not found.');
        }

        // Trả về view với thông tin của phòng
        return view('user.booking', compact('room'));
    }
}
