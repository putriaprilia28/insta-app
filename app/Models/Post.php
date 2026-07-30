<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'caption',
    ];

    // Relasi: Post dimiliki oleh 1 User
    public function user()
    {
        return $table = $this->belongsTo(User::class);
    }
}
