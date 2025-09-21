<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Api\{
    NoteController,
    TaskController,
    FolderController,
    TagController,
    ProfileController
};

/*
|--------------------------------------------------------------------------
| Auth (public)
|--------------------------------------------------------------------------
*/

// Register => create user as PENDING (no token returned)
Route::post('/auth/register', function (Request $r) {
    $data = $r->validate([
        'name'                  => 'required|string|max:255',
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|string|min:6|confirmed', // needs password_confirmation
        'company'               => 'nullable|string|max:255',
    ]);

    $u = User::create([
        'name'        => $data['name'],
        'email'       => $data['email'],
        'password'    => $data['password'], // hashed by mutator
        'company'     => $data['company'] ?? null,
        'role'        => 'user',
        'approved_at' => null,
        'approved_by' => null,
    ]);

    return response()->json([
        'message' => 'Account submitted. An administrator will review it shortly.',
        'status'  => 'pending',
    ], 201);
});

// Login => only approved users receive a token
Route::post('/auth/login', function (Request $r) {
    $cred = $r->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $u = User::where('email', $cred['email'])->first();

    if (!$u || !Hash::check($cred['password'], $u->password)) {
        return response()->json(['message' => 'Invalid credentials'], 422);
    }

    if (is_null($u->approved_at)) {
        return response()->json([
            'message' => 'Your account is pending approval.',
            'status'  => 'pending',
        ], 403);
    }

    return response()->json([
        'token' => $u->createToken('mobile')->plainTextToken,
        'user'  => [
            'id'    => $u->id,
            'name'  => $u->name,
            'email' => $u->email,
            'role'  => $u->role,
        ],
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Profile
    Route::get('/me', [ProfileController::class, 'me']);
    Route::patch('/me', [ProfileController::class, 'patch']);
    Route::post('/me/avatar', [ProfileController::class, 'uploadAvatar']);

    // App resources
    Route::apiResource('folders', FolderController::class);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('notes', NoteController::class);
    Route::apiResource('tasks', TaskController::class);

    /*
    |--------------------------------------------------------------------------
    | Admin-only (approve/reject users)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        // list pending users
        Route::get('/admin/users/pending', function () {
            return User::pending()
                ->select('id', 'name', 'email', 'company', 'created_at')
                ->orderBy('created_at', 'asc')
                ->get();
        });

        // approve a user
        Route::post('/admin/users/{user}/approve', function (Request $r, User $user) {
            if ($user->approved_at) {
                return response()->json(['message' => 'User already approved'], 409);
            }
            $user->forceFill([
                'approved_at' => now(),
                'approved_by' => $r->user()->id,
            ])->save();

            return response()->json(['message' => 'User approved']);
        });

        // reject (delete) a pending user
        Route::delete('/admin/users/{user}/reject', function (User $user) {
            if ($user->approved_at) {
                return response()->json(['message' => 'User is approved; cannot reject'], 409);
            }
            $user->delete();
            return response()->json(['message' => 'User rejected and removed']);
        });
    });
});
