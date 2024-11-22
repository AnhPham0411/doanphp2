<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Customer; // Nhập mô hình Customer
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Lấy danh sách order với phân trang
        $orders = Order::with('booking.customer')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        // Lấy giá trị booking_id từ query string
        $booking_id = $request->query('booking_id');

        // Kiểm tra và lấy thông tin booking
        if (!$booking_id || !$booking = Booking::find($booking_id)) {
            return redirect()->route('bookings.index')->withErrors('Không tìm thấy đặt phòng.');
        }

        // Lấy danh sách khách hàng (nếu cần)
        $customers = Customer::all();

        // Trả về view với thông tin booking
        // dd($booking);
        return view('admin.orders.create', compact('booking', 'customers'));
    }


    public function store(Request $request)
    {
        // Lưu lịch sử thanh toán vào bảng orders
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',  // Xác thực booking_id tồn tại trong bảng bookings
            'total_amount' => 'required|numeric',           // Kiểm tra số tiền hợp lệ
            'payment_status' => 'required|in:pending,paid', // Trạng thái thanh toán hợp lệ
            'transaction_if' => 'required|string',          // Kiểm tra transaction_if hợp lệ
        ]);
        // dd($request);

        // Tạo một đơn hàng mới từ request và lưu vào bảng orders
        Order::create([

            'booking_id' => $request->booking_id,
            'total_amount' => $request->total_amount,
            'payment_status' => $request->payment_status,
            'transaction_if' => $request->transaction_if,
        ]);

        // Xác nhận và chuyển hướng về trang danh sách đơn hàng
        return redirect()->route('orders.index')->with('success', 'Thanh toán đã được lưu thành công!');
    }


    public function show($id)
    {
        // Hiển thị chi tiết đơn thanh toán
        $order = Order::with('booking.customer')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return redirect()->route('orders.index')->withErrors('Không tìm thấy hóa đơn.');
        }

        // Lấy thông tin booking liên quan
        $booking = Booking::find($order->booking_id);
        $customers = Customer::all();

        return view('admin.orders.edit', compact('order', 'booking', 'customers'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',  // Xác thực booking_id tồn tại trong bảng bookings
            'total_amount' => 'required|numeric',           // Kiểm tra số tiền hợp lệ
            'payment_status' => 'required|in:pending,paid', // Trạng thái thanh toán hợp lệ
            'transaction_if' => 'required|string',          // Kiểm tra transaction_if hợp lệ
        ]);

        $order = Order::find($id);
        if (!$order) {
            return redirect()->route('orders.index')->withErrors('Không tìm thấy hóa đơn.');
        }

        $order->update([
            'total_amount' => $request->total_amount,
            'transaction_if' => $request->transaction_if,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('orders.index')->with('success', 'Cập nhật hóa đơn thành công.');
    }


    public function destroy($id)
    {
        // Xóa thanh toán
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đơn thanh toán đã bị xóa!');
    }
}
