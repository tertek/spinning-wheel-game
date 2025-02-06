<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public int $balance = 0;

    protected $listeners = ['balanceUpdated' => 'updateTransactions'];

    public function mount(): void
    {
        $this->balance = auth()->user()->balance;
    }

    public function topUp(): void
    {
        $user = Auth::user();
        $amount = 5;    // Amount to top up
        $user->balance += $amount;
        $user->save();

        $transaction = BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'top_up',
        ]);

        $this->balance = $user->balance;
        $this->dispatch('balanceUpdated', $transaction->id);
    }
}
?>

<div>
    <h1>Balance: {{ $balance }}</h1>

    <button wire:click="topUp">Top Up</button>
</div>
