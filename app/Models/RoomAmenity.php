<?
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Amenities;

class RoomAmenity extends Model
{
    use HasFactory;

    protected $table = 'room_amenity'; // Tên bảng

    // Khai báo quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Khai báo quan hệ với Amenity
    public function amenity()
    {
        return $this->belongsTo(Amenities::class);
    }
}
