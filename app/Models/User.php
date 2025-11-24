<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Community;

class User extends Authenticatable
{
    public function media(){ return $this->hasMany(\App\Models\MimeType::class); }


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
protected $fillable = ['name','username','email','password','display_name','avatar_path','bio','website','twitter','instagram'];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function communitiesOwned()
    {
        return $this->hasMany(Community::class, 'owner_id');
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class)
            ->withTimestamps()
            ->withPivot('role');
    }
        public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_likes')->withTimestamps();
    }
    public function posts()
    {
    return $this->hasMany(Post::class);
    }
}