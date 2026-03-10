<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
 use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);
        $token = $user->createToken('token_api')->plainTextToken;
        return response()->json([
            'status'=>'success',
            'data'=>$user,
            'token'=>$token
        ],201);
    }

    public function login(LoginRequest $request){

    $login = $request->validated();

     if (!Auth::attempt($login)) {
            return response()->json(['status' => 'errur'], 401);
        }
         $token = $request->user()->createToken('token_api')->plainTextToken;
            return response()->json([
            'status'=>'success',
            'user' => Auth::user(),
            'token' => $token
          ], 200);
    }

    
}
