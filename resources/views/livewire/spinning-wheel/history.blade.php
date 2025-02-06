<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public $transactions;

    protected $listeners = ['balanceUpdated' => 'updateTransactions'];

    public function mount()
    {
        $this->transactions = BalanceTransaction::where('user_id', Auth::id())->get()->sortByDesc('id');
    }

    public function updateTransactions($transactionId)
    {
        $transaction = BalanceTransaction::find($transactionId);
        if ($transaction && $transaction->user_id == Auth::id()) {
            $this->transactions->prepend($transaction);
        }
    }
}
?>

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
