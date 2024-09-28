<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summaries extends Model
{
    use HasFactory;
    protected $fillable = [
        'ubication_id',
        'macaddress_id',
        'type_id'
    ];

    //Funcion para la relacion del modelo de Summaries con el modelo de Datas
    public function data(){
        return $this->belongsTo(Data::class, 'summary_id');
    }

    //Funcion para la relacion del modelo de Summaries con el modelo de Ubications
    public function ubication(){
        return $this->belongsTo(Ubication::class, 'ubication_id');
    }

    //Funcion para la relacion del modelo de Summaries con el modelo de Macaddress
    public function maccaddress(){
        return $this->belongsTo(Macaddress::class, 'macaddress_id'); 
    }

    //Funcion para la relacion del modelo de Summaries con el modelo de Types
    public function type(){
        return $this->belongsTo(Type::class, 'type_id');
    }
}
