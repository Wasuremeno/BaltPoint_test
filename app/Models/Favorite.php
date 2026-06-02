<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['character_id', 'session_id', 'ip_address'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
