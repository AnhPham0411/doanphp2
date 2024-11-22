<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Images;

class Hotel extends Model
{
    protected $fillable = ['name', 'address', 'city', 'description', 'image_path'];

    public function images()
    {
        return $this->hasMany(Images::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class); // Quan hệ với rooms
    }
}
