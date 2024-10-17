<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'date',
    'time',
    'value',
    'battery',
    'sector',
    'summary_id'
  ];

  public function summary()
  {
    return $this->belongsTo(Summaries::class, 'summary_id');
  }
}
