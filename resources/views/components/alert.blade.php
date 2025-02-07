@props(['type','outcome'])

@php
switch ($type) {
    case 'status-spinning':
        $class = 'bg-yellow-100 border-yellow-400 text-yellow-700';
        $message = "You are spinning the wheel.";
        $title = 'Spinning.';
        break;

    case 'status-won':
        $class = 'bg-green-100 border-green-400 text-green-700';
        $title = 'Congratulations!';
        $message = "You won a prize.";
        break;

    case 'status-insufficient-balance':
        $class = 'bg-red-100 border-red-400 text-red-700';
        $title = 'Insufficient balance.';
        $message = "Top up your balance to spin the wheel.";
        break;

    case 'outcome-won':
        $class = 'bg-green-100 border-green-400 text-green-700';
        $title = 'Congratulations!';
        $message = "You won $outcome.";
        break;

    case 'outcome-lost':
        $class = 'bg-red-100 border-red-400 text-red-700';
        $title = 'Better luck next time.';
        $message = "You lost $outcome.";
        break;

    case 'outcome-zero':
        $class = 'bg-yellow-100 border-yellow-400 text-yellow-700';
        $title = 'No prize.';
        $message = "You won nothing.";
        break;

    default:
        $class = 'bg-blue-100 border-blue-400 text-blue-700';
        $message = "Click on spin to spin the wheel. (1 spin = 5 credits)";
        $title = 'Ready.';
        break;

    }
@endphp


<div {{ $attributes->merge(['class' => $class . ' px-4 py-3 rounded relative mb-3']) }} role="alert">
  <strong class="font-bold">{{$title}}</strong>
  <span class="block sm:inline">{{$message}}</span>
</div>
