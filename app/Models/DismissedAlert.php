<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DismissedAlert extends Model
{
  use HasFactory;
  protected $fillable = ['type', 'sector', 'alert_date', 'user_id'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
