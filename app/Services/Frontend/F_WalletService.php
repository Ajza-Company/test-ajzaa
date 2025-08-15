<?php

namespace App\Services\Frontend;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class F_WalletService
{
    public function credit(Wallet $wallet, float $amount, string $description, array $metadata = []): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $amount, $description, $metadata) {
            $transaction = $wallet->transactions()->create([
                'type' => 'credit',
                'amount' => $amount,
                'description' => $description,
                'status' => 'completed',
                'reference' => $this->generateReference(),
                'metadata' => $metadata
            ]);

            $wallet->increment('balance', $amount);

            return $transaction;
        });
    }

    public function debit(Wallet $wallet, float $amount, string $description, array $metadata = []): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $amount, $description, $metadata) {
            if ($wallet->balance < $amount) {
                throw new \Exception(trans('general.Insufficient_wallet_balance'));
            }

            $transaction = $wallet->transactions()->create([
                'type' => 'debit',
                'amount' => $amount,
                'description' => $description,
                'status' => 'completed',
                'reference' => $this->generateReference(),
                'metadata' => $metadata
            ]);

            $wallet->decrement('balance', $amount);

            return $transaction;
        });
    }

    public function transfer(Wallet $fromWallet, Wallet $toWallet, float $amount, string $description): void
    {
        DB::transaction(function () use ($fromWallet, $toWallet, $amount, $description) {
            $this->debit($fromWallet, $amount, "Transfer to wallet #{$toWallet->id}: $description");
            $this->credit($toWallet, $amount, "Transfer from wallet #{$fromWallet->id}: $description");
        });
    }

    private function generateReference(): string
    {
        return 'TXN-' . uniqid() . '-' . time();
    }
}
