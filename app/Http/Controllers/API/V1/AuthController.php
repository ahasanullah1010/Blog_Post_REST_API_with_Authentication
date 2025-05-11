<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request){
        
    
        try{
            $request->validated();
        
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            $token = $user->createToken('ABC_123')->plainTextToken;

            return response()->json([
                'user' => $user,
                'message' => 'User registered Successfully',
                'status' => 201,
                'token' => $token 
            ], 201);

        } catch(\Throwable $e){
            return response()->json([
                'message' => 'Something went wrong.',
            ], 500);
       }

    }


    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 422
            ], 422);
        }

        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'message' => 'Unauthorized',
                'status' => 401
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('ABC_123')->plainTextToken;

        return response()->json([
            'user' => $user,
            'message' => 'User loggedin Successfully',
            'status' => 200,
            'token' => $token 
        ], 200);

    }


    public function logout(){

        Auth::user()->currentAccessToken()->delete();

        // delete all tokens
        // Auth::user()->tokens()->delete();

         return response()->json([
            'message' => 'User logged out Successfully',
            'status' => 200
        ], 200);
    }
}
