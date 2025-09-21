<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me(Request $req)
    {
        return new UserResource($req->user());
    }

    public function patch(Request $req)
    {
        $data = $req->all();

        // Map camelCase → snake_case
        $map = [
            'avatarBase64' => 'avatar_base64',
            'avatarPath'   => 'avatar_path',
            'notificationsEnabled' => 'notifications_enabled',
            'chTaskReminders'      => 'ch_task_reminders',
            'chNotesUpdates'       => 'ch_notes_updates',
            'chProductNews'        => 'ch_product_news',
            'dndEnabled'           => 'dnd_enabled',
            'dndStartHour'         => 'dnd_start_hour',
            'dndEndHour'           => 'dnd_end_hour',
        ];

        foreach ($map as $from => $to) {
            if (array_key_exists($from, $data)) {
                $data[$to] = $data[$from];
                unset($data[$from]);
            }
        }

        $validated = $req->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'location' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar_base64' => 'nullable|string',
            'avatar_path' => 'nullable|string',
            'notifications_enabled' => 'nullable|boolean',
            'ch_task_reminders' => 'nullable|boolean',
            'ch_notes_updates' => 'nullable|boolean',
            'ch_product_news' => 'nullable|boolean',
            'dnd_enabled' => 'nullable|boolean',
            'dnd_start_hour' => 'nullable|integer|min:0|max:23',
            'dnd_end_hour' => 'nullable|integer|min:0|max:23',
        ]);

        $u = $req->user();

        // ✅ Don’t overwrite avatar unless explicitly provided
        if (!array_key_exists('avatar_path', $data)) {
            unset($validated['avatar_path']);
        }
        if (!array_key_exists('avatar_base64', $data)) {
            unset($validated['avatar_base64']);
        }

        $u->fill($validated)->save();

        return new UserResource($u);
    }

    public function uploadAvatar(Request $req)
    {
        $req->validate(['avatar' => 'required|image|max:3072']);

        // store() already returns relative path like "avatars/xxxx.webp"
        $path = $req->file('avatar')->store('avatars', 'public');

        $u = $req->user();
        $u->avatar_path = $path;      // ✅ only "avatars/xxx.webp"
        $u->avatar_base64 = null;
        $u->save();

        return new UserResource($u);
    }
}
