<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'no_telp', 'tgl_reservasi', 'waktu', 'jumlah'
    ];
    public function getCreatedAtAttribute()
    {
       if (!is_null($this->attributes['created_at'])) {
           return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
       }
    } 

    public function getUpdatedAtAttribute()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    } 
}
