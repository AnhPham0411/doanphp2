<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; // Tên bảng
    protected $primaryKey = 'id'; // Khóa chính
    public $timestamps = true; // Tự động cập nhật timestamps (created_at, updated_at)

    protected $fillable = [

        'total_amount',
        'status',
        'transaction_if',
        'booking_id', // Thêm booking_id vào danh sách fillable
    ];

    // Quan hệ với bảng Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function booking()
{
    return $this->belongsTo(Booking::class);
}

}
