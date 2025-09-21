<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $avatarPath = $this->avatar_path;

        if ($avatarPath) {
            // Always return a full URL to storage file
            if (!str_starts_with($avatarPath, 'http')) {
                $avatarPath = asset('storage/' . ltrim($avatarPath, '/'));
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'location' => $this->location,
            'bio' => $this->bio,

            // ✅ always consistent full URL or null
            'avatarPath' => $avatarPath,
            'avatarBase64' => $this->avatar_base64,

            'notificationsEnabled' => (bool) $this->notifications_enabled,
            'chTaskReminders' => (bool) $this->ch_task_reminders,
            'chNotesUpdates' => (bool) $this->ch_notes_updates,
            'chProductNews' => (bool) $this->ch_product_news,
            'dndEnabled' => (bool) $this->dnd_enabled,
            'dndStartHour' => $this->dnd_start_hour,
            'dndEndHour' => $this->dnd_end_hour,
            'role' => $this->role,
        ];
    }
}
