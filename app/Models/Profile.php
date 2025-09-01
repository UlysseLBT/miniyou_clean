<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Profile extends Model {
use HasFactory;
protected $fillable = ['user_id','display_name','bio','website','twitter','instagram','avatar_path'];
public function user(){ return $this->belongsTo(User::class); }
}