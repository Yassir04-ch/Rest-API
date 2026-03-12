<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
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


    public function transfer(StoreTransactionRequest $request,string $id){

      $validated = $request->validated();

      $wallet1 = Wallet::where('id',$id)->first();
      $wallet2 = Wallet::where('id',$validated['receiver_wallet_id'])->first();

      if ($wallet1->currency !== $wallet2->currency) {
        return response()->json([
            "success" => false,
            "message" => "Transfert impossible : les deux wallets doivent avoir la même devise."
        ], 400);
     }

     if($wallet1->balance < $validated['amount']){
         return response()->json([
            "success" => false,
            "message" => "Solde insuffisant. Solde actuel : ".$wallet1->balance ." ". $wallet1->currency
        ], 400);
     }
     
     $balance1 = $wallet1->balance - $validated['amount'];
     $balance2 = $wallet2->balance + $validated['amount'];
     $wallet1->update(['balance'=>$balance1 ]);
     $wallet2->update(['balance'=>$balance2 ]);

    $transactionOut = Transaction::create([
            'wallet_id' => $wallet1->id,
            'type' => 'transfer_out',
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'balance_after' => $wallet1->balance,
            'receiver_wallet_id' => $wallet2->id
        ]);   
        
     $transactionIn = Transaction::create([
        'wallet_id' => $wallet2->id,
        'type' => 'transfer_in',
        'amount' => $validated['amount'],
        'description' => $validated['description'],
        'balance_after' => $wallet2->balance,
        'send_wallet_id' => $wallet1->id
    ]);

     return response()->json([
        "success" => true,
        "message" => "Transfert effectué avec succès.",
        "data" => [
            "transaction_out" => new TransactionResource($transactionOut),
            "transaction_in" => new TransactionResource($transactionIn),
            "wallet" => new WalletResource($wallet1)
        ]
    ], 200);
      
    }

    
}
