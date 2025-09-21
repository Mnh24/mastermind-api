<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index(Request $req)
    {
        return FolderResource::collection(
            Folder::where('user_id', $req->user()->id)->orderBy('name')->get()
        );
    }
    public function store(FolderRequest $req)
    {
        $f = Folder::create([
            'user_id' => $req->user()->id,
            'name' => $req->name,
            'color_argb' => $req->color_argb ?? 0xFF94A3B8
        ]);
        return new FolderResource($f);
    }
    public function show(Request $req, Folder $folder)
    {
        abort_if($folder->user_id !== $req->user()->id, 403);
        return new FolderResource($folder);
    }
    public function update(FolderRequest $req, Folder $folder)
    {
        abort_if($folder->user_id !== $req->user()->id, 403);
        $folder->update($req->validated());
        return new FolderResource($folder);
    }
    public function destroy(Request $req, Folder $folder)
    {
        abort_if($folder->user_id !== $req->user()->id, 403);
        if ($folder->notes()->exists()) {
            return response()->json(['message' => 'Folder not empty'], 422);
        }
        $folder->delete();
        return response()->noContent();
    }
}
