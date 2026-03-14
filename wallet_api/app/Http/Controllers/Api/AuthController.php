<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

 class AuthController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
        return response()->json([
                'success'=>false,
                'message'=>'Non authentifié.'
            ],403);   
            
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
            "success" => true,
            "message" => "Inscription réussie.",
            "data" => [
                "user" => $user,
                "token" => $token
            ]
        ], 201);
        }

        public function login(LoginRequest $request)
        {
            $credentials = $request->validated();

            if (Auth::attempt($credentials)) {

                $user = Auth::user();
                $token = $user->createToken('token_api')->plainTextToken;

                return response()->json([
                    "success" => true,
                    "message" => "Connexion réussie.",
                    "data" => [
                        "user" => $user,
                        "token" => $token
                    ]
                ], 200);
            }

            return response()->json([
                "success" => false,
                "message" => "Identifiants incorrects."
            ], 401);
        }

      public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    public function profile(Request $request){

        $user = $request->user();

        return response()->json([
            "success" => true,
            "message" => "Profil utilisateur récupéré.",
            "data" => [
                "user" => $user
            ]
        ], 200);
    }
        
    }
