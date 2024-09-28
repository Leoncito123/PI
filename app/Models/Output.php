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

    public function device(){
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function type(){
        return $this->belongsTo(Type::class, 'type:id');
    }
}
