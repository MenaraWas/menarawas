<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB
        ]);

        if ($request->file('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            $media = Media::create([
                'filename' => $file->getClientOriginalName(),
                'path' => '/storage/' . $path,
                'mimetype' => $file->getMimeType(),
                'size' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            return response()->json($media, 201);
        }

        return response()->json(['message' => 'Upload failed'], 400);
    }
}