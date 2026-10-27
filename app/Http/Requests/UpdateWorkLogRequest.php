<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan user hanya bisa mengedit work log miliknya sendiri
        return $this->user()->id === $this->route('work_log')->user_id;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'worked_at' => ['sometimes', 'required', 'date'],
            'description' => ['nullable', 'string'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'category' => ['nullable', 'string', 'max:50'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'outcome' => ['nullable', 'string'],
            'blockers' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:50'],
            'status' => ['nullable', 'string', 'in:completed,in_progress,pending'],
            'links' => ['nullable', 'array'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }
}
