<?php

namespace App\Http\Controllers\api\Admin;


use App\Http\Traits\GeneralTrait;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:3'
        ]);
        try{
    
        // $credentials = $request->only(['email', 'password']);
        // if (!Auth::attempt($credentials)) {
        //     return response()->json(['message' => 'Invalid credentials'], 401);
        // }
    
        $user = User::where('email',$request->email)->first();
        if(!$user)
        {
            return new JsonResponse(['no user found'],404);
        }
        if($user){
           if(!Hash::check($request->password,$user->password)){
            return response()->json(['message' => 'password not correct'], 400);
           }
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }
    catch(Exception $e)
    {
        return $this->errorResponse('error',500);
    }
}

    public function logout(Request $request)
    {
        
        // $request->user()->tokens()->delete();
        auth()->user()->tokens()->delete();
        // Auth::guard('api')->user()->currenAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

}
