<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Http\Resources\WorkLogResource;
use App\Models\Tag;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkLogController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->workLogs()->with(['project', 'tags']);

        // Fitur pencarian & filter sederhana
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        return WorkLogResource::collection($query->latest('worked_at')->paginate(15));
    }

    public function store(StoreWorkLogRequest $request)
    {
        $workLog = $request->user()->workLogs()->create($request->validated());

        if ($request->has('tags')) {
            $this->syncTags($workLog, $request->input('tags'), $request->user()->id);
        }

        return new WorkLogResource($workLog->load(['project', 'tags']));
    }

    public function show(WorkLog $workLog)
    {
        $this->authorize('view', $workLog); // Memerlukan WorkLogPolicy
        return new WorkLogResource($workLog->load(['project', 'tags']));
    }

    public function update(UpdateWorkLogRequest $request, WorkLog $workLog)
    {
        $workLog->update($request->validated());

        if ($request->has('tags')) {
            $this->syncTags($workLog, $request->input('tags'), $request->user()->id);
        }

        return new WorkLogResource($workLog->load(['project', 'tags']));
    }

    public function destroy(WorkLog $workLog)
    {
        $this->authorize('delete', $workLog); // Memerlukan WorkLogPolicy
        $workLog->delete();

        return response()->noContent();
    }

    // Helper untuk membuat/menghubungkan tag secara polimorfik
    private function syncTags(WorkLog $workLog, array $tags, int $userId)
    {
        $tagIds = collect($tags)->map(function ($tagName) use ($userId) {
            return Tag::firstOrCreate(
                ['user_id' => $userId, 'slug' => Str::slug($tagName)],
                ['name' => $tagName]
            )->id;
        });

        $workLog->tags()->sync($tagIds);
    }
}
