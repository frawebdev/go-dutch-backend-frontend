<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
