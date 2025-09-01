<?php
namespace App\Http\Controllers;
use App\Models\Post; use App\Models\Media;
use Illuminate\Http\Request;


class PostController extends Controller {
public function index(){
    $posts = Post::with(['user','media'])->latest()->paginate(10);
    $myMedia = auth()->user()->media()->latest()->get();
    return view('posts.index', compact('posts','myMedia'));
}

public function store(Request $request){
$data = $request->validate([
'body' => ['required','string','max:1000'],
'media_id' => ['nullable','exists:media,id']
]);
$data['user_id'] = $request->user()->id;
Post::create($data);
return back()->with('status','Post publié');
}
public function destroy(Post $post){
$this->authorize('delete',$post);
$post->delete();
return back()->with('status','Post supprimé');
}
}