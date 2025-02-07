<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{

    public $transactions;
    protected $listeners = ['transactionsUpdated' => 'updateTransactions'];

    use WithPagination;


    public function mount()
    {
        $this->transactions = BalanceTransaction::where('user_id', Auth::id())->get()->sortByDesc('id')->take(9);
    }

    public function updateTransactions($transactionId)
    {
        $transaction = BalanceTransaction::find($transactionId);
        if ($transaction && $transaction->user_id == Auth::id()) {
            $this->transactions->prepend($transaction);
            $this->transactions = $this->transactions->take(9);
        }
    }

    //  pagination seems to be a bit broken / difficult to implement
    //  https://livewire.laravel.com/docs/volt#providing-additional-view-data
    // public function with(): array
    // {
    //     return [
    //         'transactions' => BalanceTransaction::where('user_id', Auth::id())->simplePaginate(10)
    //     ];
    // }

}
?>

<div class="bg-white shadow-lg rounded-lg p-4 mb-4 px-6 py-4 flex-grow">
        <div class="font-bold text-xl mb-2">Log</div>
        <table>
            <thead>
                <tr>
                    <th class="bg-gray-800 uppercase font-semibold text-xs text-white">ID</th>
                    <th class="bg-gray-800 uppercase font-semibold text-xs text-white">Amount</th>
                    <th class="bg-gray-800 uppercase font-semibold text-xs text-white">Type</th>
                    <th class="bg-gray-800 uppercase font-semibold text-xs text-white">Date</th>
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
