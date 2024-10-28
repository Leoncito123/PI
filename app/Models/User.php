<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
  use HasFactory, Notifiable, HasRoles;


  protected $fillable = [
    'name',
    'email',
    'password'
  ];
  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function ubications(): BelongsToMany
  {
    return $this->belongsToMany(Ubication::class, 'user_ubication');
  }

  public function types(): BelongsToMany
  {
    return $this->belongsToMany(Type::class, 'user_types');
  }

  public function getRoleAttribute()
  {
    return $this->roles->first()->name ?? 'guest';
  }
}
