<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorsString = implode(', ', $errorMessages);
            return response()->json([$errorsString],404);
        }

        $user = new User();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }
}
