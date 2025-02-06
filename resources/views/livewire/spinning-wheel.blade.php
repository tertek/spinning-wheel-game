
<div>
    <h1>Balance: {{ $balance }}</h1>

    <button wire:click="topUp">Top Up</button>

    <button wire:click="spin">Spin</button>

    @if($outcome !== null)
        <p>Outcome: {{ $outcome }}</p>
    @endif


</div>
