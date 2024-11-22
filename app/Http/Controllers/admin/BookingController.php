<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // Lấy danh sách đặt phòng
        $bookings = Booking::with('room', 'customer')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $hotels = Hotel::all();
        $rooms = [];

        // Kiểm tra nếu đã chọn khách sạn
        if ($request->has('hotel_id')) {
            $rooms = Room::where('hotel_id', $request->hotel_id)->get();
        }

        return view('admin.bookings.create', compact('hotels', 'rooms'));
    }




    public function store(Request $request)
    {
        // Lưu đặt phòng vào cơ sở dữ liệu
        $request->validate([
            'room_id' => 'required',
            'customer_id' => 'required',
            'check_in' => 'required|date|before_or_equal:check_out',
            'check_out' => 'required|date|after_or_equal:check_in',
            'total_price' => 'required|numeric|min:0',
        ]);


        Booking::create($request->all());
        return redirect()->route('bookings.index')->with('success', 'Đặt phòng đã được thêm thành công!');
    }

    public function edit($id)
    {
        $booking = Booking::find($id); // Tìm booking theo ID
        if (!$booking) {
            return redirect()->route('bookings.index')->withErrors('Không tìm thấy đặt phòng.');
        }

        $hotels = Hotel::all(); // Lấy danh sách khách sạn
        $rooms = Room::where('hotel_id', $booking->room->hotel_id ?? null)->get(); // Lấy phòng theo khách sạn đã chọn
        $customers = Customer::all(); // Lấy danh sách khách hàng
        // dd($booking);
        return view('admin.bookings.edit', compact('booking', 'rooms', 'customers', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        // Cập nhật thông tin đặt phòng
        $request->validate([
            'room_id' => 'required',
            'customer_id' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'total_price' => 'required|numeric',
        ]);

        $booking = Booking::find($id);
        $booking->update($request->all());
        return redirect()->route('bookings.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        // Xóa đặt phòng
        $booking = Booking::find($id);
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Đặt phòng đã bị xóa!');
    }
}
