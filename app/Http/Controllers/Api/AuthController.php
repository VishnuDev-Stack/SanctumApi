<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Traits\ResponseTraits;
use App\Models\User;
use Hash;
use Auth;

class AuthController extends Controller
{
    use ResponseTraits;
    //
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:8',
        ]);
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorsString = implode(', ', $errorMessages);
            return response()->json([$errorsString],404);
        }

        try {
            $user = new User();
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->created_by = '1';
            $user->save();
        
            return $this->success('Registration success',$user);
        } catch (\Throwable $th) {
           // throw $th;
            return $this->error('Something went wrong.');
        }  
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()], 400);
        }

        if (!$token = Auth::attempt($validator->validated())) {
            return $this->authErrorMessage('Invalid email or password');
        }

        return $this->createToken();
    }
}
