<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'worked_at' => $this->worked_at->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
            'outcome' => $this->outcome,
            'blockers' => $this->blockers,
            'solution' => $this->solution,
            'technologies' => $this->technologies ?? [],
            'status' => $this->status,
            'links' => $this->links ?? [],

            // Relasi (hanya dimuat jika menggunakan with() di Controller)
            'project' => new ProjectResource($this->whenLoaded('project')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),

            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
