<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['type','wallet_id','amount','to_wallet_id'];

    public function wallets(){
    return $this->belongsTo(Wallet::class);
    }

    public function toWallet()
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }
    
}
