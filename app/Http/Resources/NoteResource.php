<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'folderId' => $this->folder_id,
            'accent' => $this->accent_argb,
            'richJson' => $this->rich_json,
            'createdAt' => $this->created_at?->toIso8601String(),
            'modifiedAt' => $this->updated_at?->toIso8601String(),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
