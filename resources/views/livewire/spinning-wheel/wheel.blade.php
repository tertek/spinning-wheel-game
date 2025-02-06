<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public $spinning = false;
    public $rotation = 0;
    public $outcome = null;

    public function spin()
    {
        $user = Auth::user();
        $cost = 5; // Cost per spin

        if ($user->balance < $cost) {
            $this->outcome = 'Insufficient balance';
            return;
        }

        $this->spinning = true;

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

        // Determine the rotation based on the outcome
        $this->rotation = $this->getRotation($outcome);

        $this->dispatch('balanceUpdated', $spinCostTransaction->id);
        $this->dispatch('balanceUpdated', $spinOutcomeTransaction->id);

        $this->spinning = false;
    }

    private function determineOutcome()
    {
        $outcomes = [10, -5, 0]; // Example outcomes
        return $outcomes[array_rand($outcomes)];
    }

    private function getRotation($outcome)
    {
        switch ($outcome) {
            case 10:
                return 0; // Segment 1
            case -5:
                return 120; // Segment 2
            case 0:
                return 240; // Segment 3
            default:
                return 0;
        }
    }
}
?>
<div>

    <button x-bind:disabled="spinning" wire:click="spin">Spin</button>
    @if($outcome)
        <p>Outcome: {{ $outcome }}</p>
    @endif

</div>
