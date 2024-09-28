<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'identifier',
        'unit',
        'icon',
        'min_value',
        'max_value',
        'segment',
        'interval'
    ];

    public function sumarry(){
        return $this->belongsTo(Type::class,'type_id');
    }
}
