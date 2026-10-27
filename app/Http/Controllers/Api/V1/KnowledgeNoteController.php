<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\KnowledgeNoteResource;
use App\Models\KnowledgeNote;
use Illuminate\Http\Request;

class KnowledgeNoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = $request->user()->knowledgeNotes()
            ->with('tags')
            ->latest()
            ->paginate(15);

        return KnowledgeNoteResource::collection($notes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
            'concepts' => 'nullable|array',
            'code_snippets' => 'nullable|array',
            'references' => 'nullable|array',
        ]);

        $note = $request->user()->knowledgeNotes()->create($validated);

        return new KnowledgeNoteResource($note);
    }

    public function show(KnowledgeNote $knowledgeNote)
    {
        $this->authorize('view', $knowledgeNote);
        return new KnowledgeNoteResource($knowledgeNote->load(['project', 'tags']));
    }

    public function update(Request $request, KnowledgeNote $knowledgeNote)
    {
        $this->authorize('update', $knowledgeNote);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            // ...
        ]);

        $knowledgeNote->update($validated);

        return new KnowledgeNoteResource($knowledgeNote);
    }

    public function destroy(KnowledgeNote $knowledgeNote)
    {
        $this->authorize('delete', $knowledgeNote);
        $knowledgeNote->delete();

        return response()->noContent();
    }
}
