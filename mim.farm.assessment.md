# mim.farm assessment

Author: Ekin Tertemiz (tertek@proton.me)
Date: February 2025

**Note on use of AI based tools:** This protocol has been created by the author from scratch. Github Co Pilot has been used to fetch ideas but was not of high accuracy. Most of the code has been developed by the author's own thought.

## Task

**User Story:** Spinning Wheel Game with Laravel, Alpine.js, and Livewire 3 (API-Style)
Title: As a user, I want to play a spinning wheel game where I can log in, top up my balance by clicking, and have the game results stored in a database.

**Description:** The system provides a spinning wheel game built with Laravel, Alpine.js, and Livewire 3 in an API-style approach. Users can log in, increase their balance by clicking a button, and spin the wheel to win various rewards. Each spin deducts a predefined amount from the balance, and the result of the spin is stored in a database.

**Acceptance Criteria:**

1. User Authentication
   Users can sign up and log in using Laravel’s authentication system.
   Only authenticated users can access the spinning wheel feature.

2. Balance System
   Users have a balance associated with their account.
   Users can increase their balance by clicking a “Top Up” button (simulating adding credits).
   Each click adds a predefined amount (e.g., +1 credit) to the balance.
   The balance is stored in the database and updated in real-time via Livewire.

3. Spinning Wheel Mechanics
   The spinning wheel is implemented using Alpine.js for frontend interactivity.
   Users can spin the wheel by clicking a button, provided they have sufficient balance.
   Each spin costs a certain amount of credits (e.g., 5 credits per spin).
   The system randomly determines the outcome of the spin.
   Possible outcomes include various rewards (e.g., +10 credits, -5 credits, "Try again").
   The result of each spin is displayed to the user in real-time.

4. Database Storage
   Each spin event is recorded in the database with:
   User ID
   Time of spin
   Cost of spin
   Outcome (e.g., credits won/lost)
   The balance updates are also logged.

5. Real-time Updates & API Architecture
   Livewire is used to update the balance and spin results in real-time.
   An API endpoint allows fetching the current balance and spin history.
   The frontend uses Livewire for real-time UI updates without page reloads.

**Technical Stack:**
Backend: Laravel (API-based, Livewire 3)
Frontend: Alpine.js for UI interactivity
Database: MySQL or PostgreSQL
Livewire 3: Real-time updates for balance and spin results.

## Development environment

Linux PopOS! 22.04

Docker Engine 27.5.1

Visual Studio Code 1.96.4

## Implementation log

### 1. Setup Laravel environment with Sails

We will only need a Postgres Database and access them through a devcontainer in Visual Studio Code.

[Installation - Laravel 11.x - The PHP Framework For Web Artisans](https://laravel.com/docs/11.x/installation#sail-on-linux)

[Developing inside a Container](https://code.visualstudio.com/docs/devcontainers/containers)

```bash
curl -s "https://laravel.build/spinning-wheel-game?with=pgsql,mailpit&devcontainer" | bash
cd spinning-wheel-game && ./vendor/bin/sail up # spins up docker containers
./vendor/bin/sail artisan migrate # initial database migration
```

Laravel v11.41.3 (PHP v8.4.3) is now running on localhost.

*Bonus*: Initiate git so that we have it under version control: `git init && git add . && git commit -m 'Setup Laravel + Postgres with Sails' && git push`

### 2. Add Laravel auth helpers

**Laravel Breeze**

First, we will use Authentication Starter kit Laravel Breeze with Livewire.

[Starter Kits - Laravel 11.x - The PHP Framework For Web Artisans](https://laravel.com/docs/11.x/starter-kits#laravel-breeze-installation)

[Volt | Laravel Livewire](https://livewire.laravel.com/docs/volt)

```bash
composer require laravel/breeze --dev
php artisan breeze:install
```

Let's seed the database using the UserFactory to test authentication.

[Database: Seeding - Laravel 11.x - The PHP Framework For Web Artisans](https://laravel.com/docs/11.x/seeding)

```bash
php artisan db:seed
# default credentials:
# email: test@example.com
# password: password
```

### 3. Add business logic

**Models**

There are three major models to implement the business logic: User, Spin and BalanceTransaction.

**Components**

Use Livewire Volt Components to build Spinning Wheel. The base component consist of the wheel, balance, log and status.

**Events**

The communication between components is achivied through event dispatchment and event listening, mostly for updating the balance transaction, balance value, outcome and spinning status.

#### 4. Add high quality UX

The design of the Spinning Wheel has been inspired through an open source example. The integration to Alpine and Livewire, same as implementation of determinstic animation behavious has been implemented by the author from scratch.

 [Wheel of Fortune with CSS - DEV Community](https://dev.to/madsstoumann/wheel-of-fortune-with-css-p-pi-1ne9)
