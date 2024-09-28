<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Macaddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'macadddress',
        'sector',
        'longitude',
        'latitude'
    ];

    public function summary(){
        return $this->belongsTo(Summaries::class, 'macaddress_id');
    }
}
