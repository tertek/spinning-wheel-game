<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public $balance = 0;

    protected $listeners = ['balanceUpdated' => 'updateBalance'];

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
        $this->dispatch('transactionsUpdated', $transaction->id);
    }

    public function updateBalance()
    {
        $this->balance = auth()->user()->balance;
    }

}
?>

<div class="bg-white shadow-lg rounded-lg px-6 py-4 mb-4 items-center">
    <div class="font-bold text-xl mb-2 mr-4">Balance</div>

    @if($balance < 5)
    <x-alert type="status-insufficient-balance"/>
    @else
    <x-alert type="status-idle"/>
    @endif

    <div class="flex items-center space-x-4 gap-4">
        <input type="text" value="{{ $balance }}" readonly
        class="bg-gray-100 border border-gray-300 rounded-md
        px-4 py-2 text-gray-700 text-base mr-4">
        <button
            wire:click="topUp"
            class="flex-grow inline-flex px-4 py-2 text-center px-4 py-2 items-center bg-gray-800
            border border-transparent rounded-md font-semibold
            text-white uppercase hover:bg-gray-700
            focus:bg-gray-700 active:bg-gray-900 focus:outline-none
            focus:ring-2
            focus:ring-indigo-500 focus:ring-offset-2 transition
            ease-in-out duration-150">
            Top Up (5 credits)
        </button>
    </div>
</div>
