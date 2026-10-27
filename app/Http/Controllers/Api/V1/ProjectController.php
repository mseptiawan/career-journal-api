<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()->projects()
            ->withCount('workLogs')
            ->latest('started_at')
            ->get();

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_context' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date|after_or_equal:started_at',
            'status' => 'in:ongoing,completed,archived',
            'tech_stack' => 'nullable|array',
            'repo_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'achievements' => 'nullable|string',
        ]);

        $project = $request->user()->projects()->create($validated);

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return new ProjectResource($project->load(['workLogs', 'knowledgeNotes', 'tags']));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            // ... (aturan validasi lainnya sama seperti store)
        ]);

        $project->update($validated);

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return response()->noContent();
    }
}
