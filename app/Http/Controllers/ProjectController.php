<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::published()->with('user')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'link' => 'nullable|url',
            'tech_stack' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['user_id'] = auth()->id();

        return Project::create($validated);
    }

    public function show(Project $project)
    {
        if ($project->status === 'draft' && $project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return $project->load('user');
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'image_url' => 'nullable|url',
            'link' => 'nullable|url',
            'tech_stack' => 'nullable|string',
            'status' => 'in:draft,published',
        ]);

        $project->update($validated);
        return $project;
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $project->delete();
        return response()->json(null, 204);
    }
}