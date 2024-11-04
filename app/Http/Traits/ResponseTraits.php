<?php

namespace App\Http\Traits;
use App\Models\User;

trait ResponseTraits
{
    //
    public function authErrorMessage($message) {
        return response()->json(['message' => $message], 401);
    }
    public function success($message,$data) {
        return response()->json([$message, $data], 400);
    }
    public function createToken() {
        $user = auth()->user();
        return response()->json([
            'message' => 'Login successfully',
            'access_token' => $user->createToken('Api Token')->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 200);
    }


    public function error($message) {
        return response()->json([$message], 400);
    }
}
