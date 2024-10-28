<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_ubication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ubication_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ubication()
    {
        return $this->belongsTo(Ubication::class);
    }


}
