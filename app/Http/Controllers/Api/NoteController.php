<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $req)
    {
        $notes = Note::with('tags')
            ->where('user_id', $req->user()->id)
            ->latest('updated_at')->paginate(20);
        return NoteResource::collection($notes);
    }

    public function store(NoteRequest $req)
    {
        $note = Note::create(array_merge(
            $req->validated(),
            ['user_id' => $req->user()->id]
        ));
        if ($req->filled('tag_ids')) $note->tags()->sync($req->tag_ids);
        return new NoteResource($note->load('tags'));
    }

    public function show(Request $req, Note $note)
    {
        abort_if($note->user_id !== $req->user()->id, 403);
        return new NoteResource($note->load('tags'));
    }

    public function update(NoteRequest $req, Note $note)
    {
        abort_if($note->user_id !== $req->user()->id, 403);
        $note->update($req->validated());
        if ($req->has('tag_ids')) $note->tags()->sync($req->tag_ids ?? []);
        return new NoteResource($note->load('tags'));
    }

    public function destroy(Request $req, Note $note)
    {
        abort_if($note->user_id !== $req->user()->id, 403);
        $note->delete();
        return response()->noContent();
    }
}
