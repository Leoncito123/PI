<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Macaddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'macaddress',
        'sector',
        'longitude',
        'latitude'
    ];

    public function summaries(){
        return $this->hasMany(Summaries::class, 'macaddress_id');
    }

    public function ubications(){
        return $this->belongsToMany(Ubication::class, 'ubication_id');
    }
}
