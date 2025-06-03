<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        return response([
            'users' => new UserResource(User::all())
        ]);
    }

    public function update(UpdateUserRequest $request, user $user)
    {
        try {
            $validatedData = $request->validated();

            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $user->update($validatedData);

            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                'user' => new UserResource($user)
            ], 200);

        } catch (\Throwable $th) {
            return response([
                'error'=>$th->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if (Auth::id() === $user->id) {
                return response()->json([
                    'error' => 'No puedes eliminarte a ti mismo'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'message' => 'Usuario eliminado correctamente'
            ], 200);

        } catch (\Throwable $th) {
            return response([
                'error'=>$th->getMessage()
            ], 500);
        }
    }
}
