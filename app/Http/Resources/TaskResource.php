<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'time' => $this->time,
            'endTime' => $this->end_time,
            'progress' => $this->progress,
            'status' => $this->status,
            'gradientStart' => $this->gradient_start,
            'gradientEnd' => $this->gradient_end,
            'dueDate' => $this->due_date?->toDateString(),
            'createdAt' => $this->created_at?->toIso8601String(),
            'modifiedAt' => $this->updated_at?->toIso8601String(),
        ];
    }
}
