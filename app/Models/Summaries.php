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
  public function data()
  {
    return $this->hasMany(Data::class, 'summary_id');
  }

  //Funcion para la relacion del modelo de Summaries con el modelo de Ubications
  public function ubications()
  {
    return $this->belongsTo(Ubication::class, 'ubication_id');
  }

  //Funcion para la relacion del modelo de Summaries con el modelo de Macaddress
  public function macaddress()
  {
    return $this->belongsTo(Macaddress::class, 'macaddress_id');
  }

  //Funcion para la relacion del modelo de Summaries con el modelo de Types
  public function type()
  {
    return $this->belongsTo(Type::class, 'type_id');
  }
}
