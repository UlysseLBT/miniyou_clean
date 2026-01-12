<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'visibility',
        'is_private',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('role');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function joinRequests()
    {
    return $this->hasMany(\App\Models\CommunityJoinRequest::class);
    }

    public function invitations()
    {
    return $this->hasMany(\App\Models\CommunityInvitation::class);
    }
}