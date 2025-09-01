<?php
namespace App\Http\Controllers;
use App\Models\Media;
use Illuminate\Http\Request;


class MediaController extends Controller {
public function index(Request $request){
$items = Media::where('user_id',$request->user()->id)->latest()->paginate(12);
return view('media.index', compact('items'));
}
public function store(Request $request){
$data = $request->validate([
'file' => ['required','file','max:10240']
]);
$file = $data['file'];
$path = $file->store('uploads','public');
Media::create([
'user_id' => $request->user()->id,
'disk' => 'public',
'path' => $path,
'mime' => $file->getClientMimeType(),
'size' => $file->getSize(),
'original_name' => $file->getClientOriginalName(),
]);
return back()->with('status','Fichier uploadé');
}
public function destroy(Request $request, Media $media){
$this->authorize('delete',$media);
\Storage::disk($media->disk)->delete($media->path);
$media->delete();
return back()->with('status','Supprimé');
}
}