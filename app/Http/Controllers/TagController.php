<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return Tag::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:tags',
            'slug' => 'required|unique:tags',
        ]);

        return Tag::create($validated);
    }

    public function show(Tag $tag)
    {
        return $tag;
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'unique:tags,name,' . $tag->id,
            'slug' => 'unique:tags,slug,' . $tag->id,
        ]);

        $tag->update($validated);
        return $tag;
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(null, 204);
    }
}