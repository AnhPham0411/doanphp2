<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingControllerU extends Controller
{
    public function store(Request $request)
{
    // Kiểm tra dữ liệu nhận được
    // dd($request->all());

    // Validate form inputs
    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:22',
        'check_in' => 'required|date|after_or_equal:today', // Ngày check_in phải từ hôm nay trở đi
        'check_out' => 'required|date|after:check_in', // Ngày check_out phải sau ngày check_in
        'room_id' => 'required|exists:rooms,id', // Kiểm tra phòng có tồn tại
    ]);

    // Tìm hoặc tạo khách hàng
    $customer = Customer::firstOrCreate(
        ['email' => $request->input('customer_email')],
        [
            'name' => $request->full_name,
            'phone' => $request->phone,
            'point' => 0, // Khởi tạo điểm nếu là khách hàng mới
            'email' => $request->input('email'),
        ]
    );

    // Lấy phòng dựa trên room_id
    $room = Room::findOrFail($request->room_id);

    // Xác định điểm cộng dựa trên loại phòng
    $points = 0;
    switch ($room->type) {
        case 'single':
            $points = 10;
            break;
        case 'double':
            $points = 20;
            break;
        case 'suite':
            $points = 30;
            break;
    }

    // Cộng điểm cho khách hàng
    $customer->point += $points;
    $customer->save();

    // Tạo booking mới
    Booking::create([
        'room_id' => $request->room_id,
        'customer_id' => $customer->id,
        'name' => $request->full_name,
        'phone' => $request->phone,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'total_price' => $request->total_price,
        'status' => 1, // Giả sử 1 là trạng thái "đặt thành công"
    ]);

    // Chuyển hướng sau khi đặt phòng thành công
    return redirect()->route('booking.success')->with('success', 'Đặt phòng đã được thêm thành công!');
}


    public function success()
    {
        return view('user.booking_success');
    }

    // Hàm tính tổng giá phòng
    private function calculateTotalPrice($roomId, $checkinDate, $checkoutDate, $roomCount = 1)
    {
        $room = Room::findOrFail($roomId);

        // Tính số ngày ở (nếu check-in và check-out cùng ngày thì tính là 1 ngày)
        $days = (new Carbon($checkinDate))->diffInDays(new Carbon($checkoutDate)) ?: 1;

        return $room->price * $days * $roomCount;
    }
}
