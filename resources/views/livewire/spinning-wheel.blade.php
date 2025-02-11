<?php

use App\Models\User;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

?>
<div class="flex flex-col">
    <div>
        <livewire:spinning-wheel.status/>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-grow">
        <div class="md:col-span-1 flex flex-col">
            <livewire:spinning-wheel.wheel/>
        </div>

        <div class="md:col-span-1 flex flex-col">
            <livewire:spinning-wheel.balance/>
            <livewire:spinning-wheel.log/>
        </div>
    </div>
</div>
