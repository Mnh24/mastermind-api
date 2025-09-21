<?php
// app/Http/Controllers/Api/AdminUserController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function pending()
    {
        return User::whereNull('approved_at')->orderBy('created_at', 'desc')->get();
    }

    public function approve(User $user, Request $request)
    {
        if ($user->approved_at) {
            return response()->json(['message' => 'Already approved'], 409);
        }
        $user->approved_at = now();
        $user->approved_by = $request->user()->id;
        $user->save();

        return response()->json(['message' => 'Approved']);
    }

    public function reject(User $user)
    {
        // your choice: delete or mark as rejected; here we delete:
        if (is_null($user->approved_at)) {
            $user->delete();
            return response()->json(['message' => 'Rejected & deleted']);
        }
        return response()->json(['message' => 'User already approved'], 409);
    }
}
