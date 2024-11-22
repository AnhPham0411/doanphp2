<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    // Khai báo bảng nếu tên bảng không theo quy tắc Laravel
    protected $table = 'images';

    // Khai báo các trường có thể fill
    protected $fillable = [
        'hotel_id',
        'room_id',
        'amenities_id',
        'image_path',
        'note',
    ];

    // Định nghĩa quan hệ với các model khác nếu có
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }


    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function amenity()
    {
        return $this->belongsTo(Amenities::class, 'amenities_id'); // Chỉ ra khóa ngoại
    }
}
