<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Images;
use App\Models\Amenities;

class Room extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu khác với quy tắc mặc định
    protected $table = 'rooms';

    // Các thuộc tính có thể gán hàng loạt
    protected $fillable = [
        'room_number',  // Tên phòng
        'type',         // Loại phòng
        'capacity',     // Số lượng người tối đa
        'price',        // Giá phòng
        'hotel_id',     // ID khách sạn (liên kết với bảng hotels)
    ];

    // Khai báo quan hệ với mô hình Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // Khai báo quan hệ với mô hình Image
    public function images()
    {
        return $this->hasMany(Images::class);
    }

    // Khai báo quan hệ với mô hình Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenities::class, 'room_amenity');
    }
}
