<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // GET /api/posts
    public function index(Request $request)
    {
        $query = Post::published()->with('user', 'tags', 'categories');

        // Filter by type
        if ($request->has('type')) {
            $query->type($request->type);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%")
                  ->orWhere('content', 'like', "%$search%");
        }

        return $query->orderBy('published_at', 'desc')
                     ->paginate(10);
    }

    // GET /api/posts/{post}
    public function show(Post $post)
    {
        // Check if draft and not the author
        if ($post->status === 'draft' && (!auth()->check() || $post->user_id !== auth()->id())) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return $post->load('user', 'tags', 'categories');
    }

    // POST /api/posts (admin only)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'type' => 'required|in:blog,news',
            'status' => 'required|in:draft,published',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(),
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'user_id' => auth()->id(),
        ]);

        return response()->json($post, 201);
    }

    // PUT /api/posts/{post} (admin only)
    public function update(Request $request, Post $post)
    {
        // Authorization
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'excerpt' => 'nullable|string|max:500',
            'type' => 'in:blog,news',
            'status' => 'in:draft,published',
        ]);

        $post->update($validated);

        // If status changed to published, set published_at
        if ($request->has('status') && $request->status === 'published' && !$post->published_at) {
            $post->update(['published_at' => now()]);
        }

        return response()->json($post);
    }

    // DELETE /api/posts/{post} (admin only)
    public function destroy(Post $post)
    {
        // Authorization
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}