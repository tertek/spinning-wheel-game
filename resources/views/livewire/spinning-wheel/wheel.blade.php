<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{

    public $outcome;
    public $segmentType;
    public $lastSpinOutcomeTransactionId;
    public $isSpinning = false;
    public $previousEndDegree = 0;


    const OUTCOMES = [
        'A' => 0,
        'B' => -5,
        'C' => 10,
    ];

    public function spin()
    {
        if($this->isSpinning) {
            return;
        }

        $user = Auth::user();
        $cost = 5;

        if ($user->balance < $cost) {
            return;
        }

        $user->balance -= $cost;
        list($outcome, $segmentType) = $this->determineOutcome();
        $this->segmentType = $segmentType;
        $this->outcome = $outcome;
        $user->balance += $outcome;
        $user->save();

        $this->dispatch('wheelSpun', segmentType: $segmentType);
        $this->dispatch('spinningUpdated', true);

        $spinCostTransaction = BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => -$cost,
            'type' => 'spin_cost',
        ]);
        //  Can be called immediately after the transaction is created
        $this->dispatch('transactionsUpdated', $spinCostTransaction->id);

        //  Shall be updated when the outcome is "shown" to the user
        $spinOutcomeTransaction = BalanceTransaction::create([
            'user_id' => $user->id,
            'amount' => $outcome,
            'type' => 'spin_outcome',
        ]);

        $this->lastSpinOutcomeTransactionId = $spinOutcomeTransaction->id;

        $this->balance = $user->balance;
        $this->outcome = $outcome;
    }

    public function updateAfterSpin() {
        $this->isSpinning = false;
        $this->dispatch('transactionsUpdated', $this->lastSpinOutcomeTransactionId);
        $this->dispatch('spinningUpdated', false);
        $this->dispatch('balanceUpdated');
        $this->dispatch('outcomeUpdated', $this->outcome);
    }

    private function determineOutcome()
    {
        $randomKey = array_rand(self::OUTCOMES);
        $randomValue = self::OUTCOMES[$randomKey];
        //$outcomes = [10, -5, 0]; // Example outcomes
        return array($randomValue, $randomKey);
    }

}
?>
    <div class="bg-white shadow-lg rounded-lg p-4 mb-4 px-6 py-4 flex-grow">
        <div class="font-bold text-xl mb-2">Spinning Wheel</div>
        <fieldset class="ui-wheel-of-fortune">
            <ul>
                <li>0</li>
                <li>-5</li>
                <li>+10</li>
                <li>0</li>
                <li>-5</li>
                <li>+10</li>
                <li>0</li>
                <li>-5</li>
                <li>+10</li>
                <li>0</li>
                <li>-5</li>
                <li>+10</li>
            </ul>
            <button wire:click="spin" type="button">SPIN</button>
        </fieldset>
    </div>

    @script
    <script>
    $wire.on('wheelSpun', (event) => {
            const segmentType = event.segmentType;
            console.log("Determined Segment Type: " + segmentType);
            wheelOfFortune();
        });

    function wheelOfFortune() {
        if ($wire.isSpinning) {
            return;
        };

        $wire.set('isSpinning', true);

        const selector = '.ui-wheel-of-fortune';
        const node = document.querySelector(selector);
        if (!node) return;

        const spin = node.querySelector('button');
        const wheel = node.querySelector('ul');

        let animation;
        let previousEndDegree = $wire.previousEndDegree;
        let segmentType = $wire.segmentType

        const randomAdditionalDegrees = getRandomRotationForSegment(segmentType, previousEndDegree);
        const newEndDegree = previousEndDegree + randomAdditionalDegrees;

        animation = wheel.animate([
            { transform: `rotate(${previousEndDegree}deg)` },
            { transform: `rotate(${newEndDegree}deg)` }
        ], {
            duration: 4000,
            direction: 'normal',
            easing: 'cubic-bezier(0.440, -0.205, 0.000, 1.130)',
            fill: 'forwards',
            iterations: 1
        });

        animation.onfinish = (event) => {
            console.log('Animation finished');
            $wire.call('updateAfterSpin');
        };

        let predictedSegmentType = calcSegmentType(previousEndDegree, randomAdditionalDegrees);
        console.log("Predicted Segment Type: " + predictedSegmentType); // for testing

        previousEndDegree = newEndDegree % 360;
        $wire.set('previousEndDegree', previousEndDegree);
    }

    function getRandomRotationForSegment(segmentType, previousEndDegree) {

        //  determine base rotation from segment type A,B or C
        const baseRotationsDeg = {
            'A': 0,
            'C': 30,
            'B': 60
        };
        const baseRotation = baseRotationsDeg[segmentType];

        const baseVariation = 3 * 360 / 12; // 3 types of segments per 360 degrees with a total of 12 segments

        const newRotation = 1800 + baseRotation + randomIntFromInterval(1, 3) * baseVariation; //   let's spin at least 5 rounds + some variation within 1 round
        const randomeOffset = Math.random() * 15 * getRandomSign(); //  add positive or negative offset between 0 and 15 degrees for more realness
        const adjustedRotation = newRotation + randomeOffset - previousEndDegree ;  //  substract the previous end degree to get the actual rotation

        return adjustedRotation;
    }

    function randomIntFromInterval(min, max) { // min and max included
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    function calcSegmentType(startDegree, rotationDegree) {

        const totalRotation = startDegree + rotationDegree;
        const normalizedRotation = totalRotation % 360;
        //  add an offset of 15 degrees since the first animation starts in the middle the segment
        const offsetRotation = (normalizedRotation + 15) % 360;
        const segmentIndex = Math.floor(offsetRotation / 30) % 3;

        let segmentType;
        switch (segmentIndex) {
            case 0:
                segmentType = 'A';
                break;
            case 1:
                segmentType = 'C';
                break;
            case 2:
                segmentType = 'B';
                break;
        }

        return segmentType;
    }

    function getRandomSign() {
        return Math.round(Math.random()) * 2 - 1;
    }

</script>
@endscript
