<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
 use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallet = WalletResource::collection(Wallet::with('transactions')->with('user')->where('user_id',Auth::id())->get());
        return response()->json([
          'status'=>'success',
          'data'=>$wallet
        ],200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreWalletRequest $request):  JsonResponse
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();
        $wallet = Wallet::create($validated);
        $wallet->load('transactions');
        return response()->json([
        'status' => 'success',
        'message' => 'wallet créé avec succès',
        'data' => $wallet
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
