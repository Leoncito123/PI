<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'ubication_id'
    ];

    public function ubications (){
        return $this->belongsTo(Ubication::class, 'ubication_id');
    }

    public function output(){
        return $this->belongsTo(Output::class, 'output_id');
    }

}
