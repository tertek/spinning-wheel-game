<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $transactions;
    public $page = 0;
    public $maxPages;
    private $perPage = 9;

    protected $listeners = ['transactionsUpdated' => 'updateTransactions'];

    public function mount()
    {
        $this->transactions = BalanceTransaction::where('user_id', Auth::id())
        ->get()->sortByDesc('id')->take($this->perPage);
        $this->maxPages = $this->maxPages();

    }

    private function maxPages()
    {
        return ceil(BalanceTransaction::where('user_id', Auth::id())->count() / $this->perPage);
    }

    public function nextPage()
    {
        if($this->transactions->count() < $this->perPage) {
            return;
        }
        $this->paginate($this->page + 1);
    }

    public function previousPage()
    {
        if($this->page == 0) {
            return;
        }
        $this->paginate($this->page - 1);
    }

    private function paginate($page)
    {
        $this->page = $page;
        $this->transactions = BalanceTransaction::where('user_id', Auth::id())
        ->get()->sortByDesc('id')->skip($this->perPage * $page)->take($this->perPage);
    }

    public function updateTransactions($transactionId)
    {
        $this->page = 0;
        $this->paginate($this->page);
        $this->maxPages = $this->maxPages();

        $transaction = BalanceTransaction::find($transactionId);
        if ($transaction && $transaction->user_id == Auth::id()) {
            $this->transactions->prepend($transaction);
            $this->transactions = $this->transactions->take($this->perPage);
        }
    }

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
                <tr wire:key="{{ $transaction->id }}">
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
        <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 {{ $page == 0 ? 'cursor-not-allowed opacity-50' : '' }}">
            Newer
        </button>
        <div class="relative inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
            Page {{ $page + 1 }} of {{ $maxPages }}
        </div>
        <button wire:click="nextPage" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 {{ $page+1 == $maxPages ? 'cursor-not-allowed opacity-50' : '' }}">
            Older
        </button>
    </nav>
</div>
