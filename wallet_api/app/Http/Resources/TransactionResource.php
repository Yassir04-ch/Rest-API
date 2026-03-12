<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            "id" => $this->id,
            "wallet_id" => $this->wallet_id,
            "type" => $this->type,
            "amount" => $this->amount,
            "receiver_wallet_id"=>$this->receiver_wallet_id,
            "send_wallet_id"=>$this->send_wallet_id,
            "description" => $this->description,
            "balance_after" => $this->balance_after,
            "created_at" => $this->created_at,
            "wallet"=>new WalletResource($this->whenLoaded('wallet'))
        ];
    }

}
