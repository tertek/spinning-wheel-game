<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;

class BalanceTransactions extends Component
{
    public $transactions;

    public function mount()
    {
        $this->transactions = BalanceTransaction::where('user_id', Auth::id())->get();
    }

    public function render()
    {
        return view('livewire.balance-transactions');
    }
}
