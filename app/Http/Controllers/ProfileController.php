<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller {
public function edit(){
$user = Auth::user();
$profile = $user->profile()->firstOrCreate([]);
return view('profile.edit', compact('user','profile'));
}
public function update(Request $request){
$data = $request->validate([
'display_name' => ['nullable','string','max:100'],
'bio' => ['nullable','string','max:1000'],
'website' => ['nullable','url'],
'twitter' => ['nullable','string','max:50'],
'instagram' => ['nullable','string','max:50'],
'avatar' => ['nullable','image','max:4096'],
]);
$user = $request->user();
$profile = $user->profile()->firstOrCreate([]);
if($request->hasFile('avatar')){
$path = $request->file('avatar')->store('avatars','public');
$data['avatar_path'] = $path;
}
$profile->update($data);
return back()->with('status','Profil mis à jour');
}
}