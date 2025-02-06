<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public int $balance = 0;
    public $transactions;

    protected $listeners = ['balanceUpdated' => 'updateTransactions'];

    public function mount(): void
    {
        $this->balance = auth()->user()->balance;
        $this->transactions = BalanceTransaction::where('user_id', Auth::id())->get()->sortByDesc('id');
    }

    public function updateTransactions($transactionId)
    {
        $transaction = BalanceTransaction::find($transactionId);
        if ($transaction && $transaction->user_id == Auth::id()) {
            $this->transactions->prepend($transaction);
        }
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

        $spinCostTransaction = BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => -$cost,
            'type' => 'spin_cost',
        ]);


        $spinOutcomeTransaction = BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => $outcome,
            'type' => 'spin_outcome',
        ]);

        $this->balance = $user->balance;
        $this->outcome = $outcome;

        $this->dispatch('balanceUpdated', $spinCostTransaction->id);
        $this->dispatch('balanceUpdated', $spinOutcomeTransaction->id);
    }

    private function determineOutcome()
    {
        $outcomes = [10, -5, 0]; // Example outcomes
        return $outcomes[array_rand($outcomes)];
    }

}
?>
<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-white shadow-lg rounded-lg p-4 mb-4">
            Spinning Wheel
        </div>
        <div class="md:col-span-1 bg-white shadow-lg rounded-lg p-4 mb-4">
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">Balance</div>
                <p class="text-gray-700 text-base">
                {{ $balance }}
                </p>
            </div>
            <div class="px-6 pt-4 pb-2">
                <button
                    wire:click="topUp"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Top Up
                </button>
                <button wire:click="spin" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Spin</button>
            </div>
        </div>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <style>
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

</style>
</div>


