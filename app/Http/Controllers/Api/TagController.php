<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $req)
    {
        return TagResource::collection(
            Tag::where('user_id', $req->user()->id)->orderBy('name')->get()
        );
    }
    public function store(TagRequest $req)
    {
        $name = strtolower(ltrim($req->name, '#'));
        $tag = Tag::firstOrCreate([
            'user_id' => $req->user()->id,
            'name' => $name
        ]);
        return new TagResource($tag);
    }
    public function show(Request $req, Tag $tag)
    {
        abort_if($tag->user_id !== $req->user()->id, 403);
        return new TagResource($tag);
    }
    public function update(TagRequest $req, Tag $tag)
    {
        abort_if($tag->user_id !== $req->user()->id, 403);
        $tag->update(['name' => strtolower(ltrim($req->name, '#'))]);
        return new TagResource($tag);
    }
    public function destroy(Request $req, Tag $tag)
    {
        abort_if($tag->user_id !== $req->user()->id, 403);
        $tag->delete();
        return response()->noContent();
    }
}
