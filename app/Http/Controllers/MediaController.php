<?php
namespace App\Http\Controllers;

use App\Models\MimeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class MediaController extends Controller {
    public function index() {
        $mediaItems = MimeType::orderBy('created_at', 'desc')->paginate(10);
        return view('media.index', compact('mediaItems'));
    }

    public function store(Request $request) {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $path = $request->file('file')->store('media', 'public');

        MimeType::create([
            'filename' => basename($path),
            'filepath' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('media.index')->with('success', 'Media uploaded successfully.');
    }

    public function destroy(Media $media) {
        // Delete the file from storage
        Storage::disk('public')->delete($media->filepath);

        // Delete the database record
        $media->delete();

        return redirect()->route('media.index')->with('success', 'Media deleted successfully.');
    }
}