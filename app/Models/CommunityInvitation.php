<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityInvitation extends Model
{
    protected $fillable = [
        'community_id','inviter_id','invitee_id','token','status','expires_at','accepted_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function community() { return $this->belongsTo(Community::class); }
}
