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
        $wallets = Wallet::with('transactions')->where('user_id', Auth::id())->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des wallets récupérée.',
            'data' => [
                'wallets' => WalletResource::collection($wallets)
            ]
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreWalletRequest $request):  JsonResponse
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();
        $validated['balance'] = 0.00;
        $wallet = Wallet::create($validated);
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
       $wallet = Wallet::find($id);

      if(!$wallet){
        return response()->json([
            'success' => false,
            'message' => 'Wallet introuvable.'
        ], 404);
      }

      if ($wallet->user_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => "Vous n'êtes pas autorisé à accéder à ce wallet."
        ], 403);
      }

        return response()->json([
            'success' => true,
            'message' => 'Détail du wallet récupéré.',
            'data' => [
                'wallets' => new WalletResource($wallet)
            ]
        ], 200);
        
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
