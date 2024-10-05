<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubication extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sector',
        'longitude',
        'latitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function summaries()
    {
        return $this->hasMany(Summaries::class, 'ubication_id');
    }

    public function devices(){
        return $this->belongsToMany(Macaddress::class, 'ubication_id'); 
    }

}
