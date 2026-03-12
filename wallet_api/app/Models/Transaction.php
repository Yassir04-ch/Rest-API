<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['wallet_id','type','amount','description','balance_after',
    'receiver_wallet_id','sender_wallet_id'];

    public function wallet(){
    return $this->belongsTo(Wallet::class);
    }

      public function receiverWallet()
    {
        return $this->belongsTo(Wallet::class, 'receiver_wallet_id');
    }

    public function senderWallet()
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet_id');
    }
    
}
