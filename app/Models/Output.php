<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'dev_id',
        'status',
        'type_id'
    ];

    public function devices(){
        return $this->belongsTo(Device::class, 'dev_id');
    }

    public function types(){
        return $this->belongsTo(Type::class, 'type_id');
    }
}
