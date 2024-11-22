<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\RoomsControllerU;
use App\Http\Controllers\User\BookingCOntrollerU;

// Routes cho admin với middleware xác thực
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/admin/hotels', HotelController::class);
    Route::resource('/admin/rooms', RoomController::class);
    Route::resource('/admin/bookings', BookingController::class);
    Route::resource('/admin/customers', CustomerController::class);
    Route::resource('/admin/amenities', AmenitiesController::class);


    //đây là của hotels
    // Route thêm khách sạn mới
    Route::get('hotels', [HotelController::class, 'index'])->name('hotels.index');

    Route::get('hotels/create', [HotelController::class, 'create'])->name('hotels.create');
    Route::post('hotels', [HotelController::class, 'store'])->name('hotels.store');
    // Route chỉnh sửa khách sạn
    Route::get('hotels/{id}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
    Route::put('hotels/{id}', [HotelController::class, 'update'])->name('hotels.update');
    // Route xóa khách sạn
    Route::delete('hotels/{id}', [HotelController::class, 'destroy'])->name('hotels.destroy');
    // đây là của rooms
    Route::get('rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('rooms', [RoomController::class, 'store'])->name('rooms.store');
    // Route chỉnh sửa phòng
    Route::get('rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    // Route xóa phòng
    Route::delete('rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // đây là của amenities
    // Hiển thị danh sách tiện nghi (index)
    Route::get('amenities', [AmenitiesController::class, 'index'])->name('amenities.index');
    // Hiển thị form tạo mới tiện nghi (create)
    Route::get('amenities/create', [AmenitiesController::class, 'create'])->name('amenities.create');
    // Lưu tiện nghi mới vào cơ sở dữ liệu (store)
    Route::post('amenities', [AmenitiesController::class, 'store'])->name('amenities.store');
    // Hiển thị thông tin chi tiết tiện nghi (show) (nếu cần)
    Route::get('amenities/{id}', [AmenitiesController::class, 'show'])->name('amenities.show');
    // Hiển thị form chỉnh sửa tiện nghi (edit)
    Route::get('amenities/{id}/edit', [AmenitiesController::class, 'edit'])->name('amenities.edit');
    // Cập nhật tiện nghi (update)
    Route::put('amenities/{id}', [AmenitiesController::class, 'update'])->name('amenities.update');
    // Xóa tiện nghi (destroy)
    Route::delete('amenities/{id}', [AmenitiesController::class, 'destroy'])->name('amenities.destroy');

    //search
    Route::post('/search', [HotelController::class, 'search'])->name('hotels.search');

    //booking
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    // Route tạo booking mới
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    // Route hiển thị chi tiết booking
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    // Route chỉnh sửa booking
    Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    // Route để lấy danh sách phòng theo hotel_id
    Route::get('/api/rooms', [RoomController::class, 'getRoomsByHotel']);
    // Route xóa booking
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');


    //order
    Route::get('/orders', [OrderController::class,'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    //customer
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    // Hiển thị form thêm khách hàng
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    // Lưu khách hàng mới
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    // Hiển thị chi tiết khách hàng
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    // Hiển thị form chỉnh sửa khách hàng
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    // Cập nhật thông tin khách hàng
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    // Xóa khách hàng
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

// Routes cho đăng nhập và đăng ký
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin/register', [RegisterController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [RegisterController::class, 'register'])->name('admin.register.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');


//user
Route::get('/', [AdminController::class, 'index'])->name('user.index');
Route::get('/rooms/type/{type}', [RoomsControllerU::class, 'showRoomsByType'])->name('rooms.byType');

//booking
Route::get('/room/{id}/booking', [RoomsControllerU::class, 'showBookingForm'])->name('rooms.booking');
Route::post('/booking/store', [BookingControllerU::class, 'store'])->name('booking.store');
Route::get('/booking/success', [BookingControllerU::class, 'success'])->name('booking.success');



