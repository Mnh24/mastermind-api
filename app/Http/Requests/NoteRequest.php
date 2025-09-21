<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'folder_id' => 'nullable|exists:folders,id',
            'accent_argb' => 'nullable|integer|min:0',
            'rich_json' => 'nullable|string',
            'tag_ids' => 'array',
            'tag_ids.*' => 'integer|exists:tags,id',
        ];
    }
}
