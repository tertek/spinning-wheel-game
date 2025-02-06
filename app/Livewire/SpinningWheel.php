<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\BalanceTransaction;

class SpinningWheel extends Component
{
    public $balance;
    public $outcome;

    public function mount()
    {
        $this->balance = Auth::user()->balance;

    }

    public function topUp()
    {
        $user = Auth::user();
        $amount = 5;    // Amount to top up
        $user->balance += $amount;
        $user->save();

        BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'top_up',
        ]);

        $this->balance = $user->balance;
    }

    public function spin()
    {
        $user = Auth::user();
        $cost = 5;

        if ($user->balance < $cost) {
            $this->outcome = 'Insufficient balance';
            return;
        }

        $user->balance -= $cost;
        $outcome = $this->determineOutcome();
        $user->balance += $outcome;
        $user->save();

        BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => -$cost,
            'type' => 'spin_cost',
        ]);



        BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => $outcome,
            'type' => 'spin_outcome',
        ]);

        $this->balance = $user->balance;
        $this->outcome = $outcome;
    }

    private function determineOutcome()
    {
        $outcomes = [10, -5, 0];
        return $outcomes[array_rand($outcomes)];
    }

    public function render()
    {
        return view('livewire.spinning-wheel');
    }
}
