<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่สามารถบันทึกได้
    protected $fillable = ['room_id', 'day', 'slot', 'name', 'reason', 'booking_date'];
}
