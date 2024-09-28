<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubication extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sector',
        'longitude',
        'latitude'
    ];

    public function summarie()
    {
        return $this->belongsTo(Summaries::class, 'id_summary');
    }

}
