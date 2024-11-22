<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenities extends Model
{
    use HasFactory;

    protected $fillable = ['name','description']; // Các trường được phép điền

    // Định nghĩa quan hệ với model Images
    public function images() // Chắc chắn tên phương thức là 'images'
    {
        return $this->hasMany(Images::class, 'amenities_id'); // Chỉ ra khóa ngoại
    }
    public function rooms()
{
    return $this->belongsToMany(Room::class, 'room_amenity');
}

}
