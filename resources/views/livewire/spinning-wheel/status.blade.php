<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public $balance = 0;
    public $isSpinning = false;
    public $outcome;

    protected $listeners = [
        'spinningUpdated' => 'updateSpin',
        'outcomeUpdated' => 'updateOutcome',
    ];

    public function updateSpin($isSpinning)
    {
        $this->isSpinning = $isSpinning;
    }

    public function updateOutcome($outcome)
    {
        $this->outcome = $outcome;
    }

    public function mount(): void
    {
        $this->balance = auth()->user()->balance;
    }
}
?>

<div class="bg-white shadow-lg rounded-lg px-6 py-4 mb-4">
    <div class="font-bold text-xl mb-2">Status</div>
    @if($isSpinning)
    <x-alert type="status-spinning"/>
    @else

        @if($outcome > 0)
            <x-alert type="outcome-won" :outcome="$outcome"/>
        @elseif($outcome < 0)
            <x-alert type="outcome-lost" :outcome="$outcome"/>
        @elseif($outcome == 0)
            <x-alert type="outcome-zero"/>
        @endif
    @endif


</div>
