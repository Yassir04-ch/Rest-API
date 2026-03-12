<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $transactions = TransactionResource::collection(Transaction::with('wallet')->get());
        return response()->json([
            'status'=>'success',
            'data'=>$transactions,
        ],200);
       
    }

    /**
     * Store a newly created resource in storage.
     */
   

    public function deposit(StoreTransactionRequest $request,string $id)
    {
     $validated = $request->validated();
     $wallet = Wallet::where('id',$id)->first();
    
     $balance = $wallet->balance + $validated['amount'];
     $wallet->update(['balance'=>$balance ]);

      $validated['type'] = 'deposit';
      $validated['wallet_id'] = $wallet->id;
      $validated['balance_after'] = $balance;
      $transaction = Transaction::create($validated);

      $transaction->load('wallet');
        return response()->json([
        'status' => true,
        'message' => 'Dépôt effectué avec succès.',
        'data' =>new TransactionResource($transaction)
        ], 201);
    }

    
   public function withdraw(StoreTransactionRequest $request,string $id)
    {
     $validated = $request->validated();
     $wallet = Wallet::where('id',$id)->first();

      if($wallet->balance < $validated['amount']){
        return response()->json([
        'success' => false,
        'message' => 'Solde insuffisant. Solde actuel : '.$wallet->balance
        ],422);
     }
    
     $balance = $wallet->balance - $validated['amount'];
     $wallet->update(['balance'=>$balance ]);

      $validated['type'] = 'withdraw';
      $validated['wallet_id'] = $wallet->id;
      $validated['balance_after'] = $balance;
      $transaction = Transaction::create($validated);

      $transaction->load('wallet');
        return response()->json([
        'status' => true,
        'message' => 'Retrait effectué avec succès',
        'data' =>new TransactionResource($transaction)
        ], 201);
    }


    public function transfer(StoreTransactionRequest $request){

     $validated = $request->validated();

      $wallet1 = Wallet::where('id',$validated['wallet_id'])->first();
     $wallet2 = Wallet::where('id',$validated['to_wallet_id'])->first();
     if($wallet1->balance < $validated['amount']){
        return response()->json([
        'status' => 'errur',
        'message' => 'balance de wallet est insifisont'
        ],422);
     }
     
     $balance1 = $wallet1->balance - $validated['amount'];
     $balance2 = $wallet2->balance + $validated['amount'];
     $wallet1->update(['balance'=>$balance1 ]);
     $wallet2->update(['balance'=>$balance2 ]);

      $transaction = Transaction::create($validated);
      $transaction->load(['wallet','toWallet']);
        return response()->json([
        'status' => 'success',
        'message' => 'argent est transfer avec success',
        'data' => $transaction
        ], 201);
    }

    
}
