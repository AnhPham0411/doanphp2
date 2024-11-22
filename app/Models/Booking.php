<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    // Các trường được phép gán hàng loạt
    protected $fillable = [
        'room_id',
        'customer_id',
        'name',         // Thêm trường name
        'phone',
        'check_in',
        'check_out',
        'total_price',
        'status'        // Thêm trường status
    ];

    // Quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Quan hệ với Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
