<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityJoinRequest extends Model
{
    protected $fillable = [
        'community_id','user_id','status','message','handled_by','handled_at'
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function community() { return $this->belongsTo(Community::class); }
    public function user() { return $this->belongsTo(User::class); }
}
