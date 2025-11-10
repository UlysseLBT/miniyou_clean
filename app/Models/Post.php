<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    /**
     * @method static \Illuminate\Database\Eloquent\Factories\Factory factory(...$parameters)
     */

    protected $fillable = [
        'user_id',
        'content',
        'media_url',
        'media_disk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}