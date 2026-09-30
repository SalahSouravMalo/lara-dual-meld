@php
    $players = $room->players->map(fn($roomPlayer) => $roomPlayer->player)->values();
    $playerNames = $players->pluck('name');
    $authPlayerIndex = $players->search(fn($player) => $player->is(auth()->user()));
    $hand = [
        'ace-of-hearts',
        'seven-of-hearts',
        'king-of-hearts',
        'two-of-spades',
        'four-of-clubs',
        'nine-of-diamonds',
        'queen-of-clubs',
        'ten-of-spades',
    ];
    $cardLabels = [
        'ace-of-hearts' => 'Ace of hearts',
        'seven-of-hearts' => 'Seven of hearts',
        'king-of-hearts' => 'King of hearts',
        'two-of-spades' => 'Two of spades',
        'four-of-clubs' => 'Four of clubs',
        'nine-of-diamonds' => 'Nine of diamonds',
        'queen-of-clubs' => 'Queen of clubs',
        'ten-of-spades' => 'Ten of spades',
    ];
@endphp

<x-layouts.auth title="{{ __('Room :code', ['code' => $room->code]) }} - {{ config('app.name') }}"
    bodyClass="bg-gray-50 dark:bg-gray-950">
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <header class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-fg-brand">{{ __('Room') }}</p>
                <h1 class="text-3xl font-bold tracking-tight text-heading sm:text-4xl">{{ $room->code }}</h1>
            </div>
            <div class="rounded-full border border-default bg-neutral-primary px-4 py-2 text-sm text-body shadow-xs">
                {{ __(':count/4 players', ['count' => $players->count()]) }}
            </div>
        </header>

        {{-- Waiting state --}}
        <livewire:rooms.waiting :room="$room" />

        {{-- In-progress state --}}
        <section class="hidden rounded-2xl border border-default bg-neutral-primary-soft p-4 shadow-xs sm:p-8"
            aria-label="{{ __('Game in progress') }}">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <span
                        class="inline-flex items-center rounded-full bg-success-soft px-3 py-1 text-xs font-semibold uppercase tracking-wide text-fg-success-strong">
                        {{ __('In progress') }}
                    </span>
                    <h2 class="mt-3 text-2xl font-bold text-heading">{{ __('Your turn') }}</h2>
                </div>
                <div class="rounded-xl border border-warning-medium bg-warning-soft px-4 py-3 text-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-fg-warning-strong">
                        {{ __('Turn time') }}</p>
                    <p class="mt-1 text-2xl font-bold tabular-nums text-heading">00:27</p>
                </div>
            </div>

            <div
                class="relative mx-auto min-h-[620px] max-w-5xl overflow-hidden rounded-2xl bg-emerald-950 p-4 text-white shadow-inner sm:p-8">
                <div class="pointer-events-none absolute inset-4 rounded-xl border border-emerald-800/70 sm:inset-8">
                </div>

                <div class="absolute left-1/2 top-6 -translate-x-1/2 text-center">
                    <p class="text-xs uppercase tracking-widest text-emerald-300">{{ __('Top player') }}</p>
                    <p class="mt-1 font-semibold">{{ $playerNames[0] ?? __('Waiting') }}</p>
                </div>
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-center sm:left-10">
                    <p class="text-xs uppercase tracking-widest text-emerald-300">{{ __('Left') }}</p>
                    <p class="mt-1 max-w-20 truncate font-semibold">{{ $playerNames[1] ?? __('Waiting') }}</p>
                </div>
                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-center sm:right-10">
                    <p class="text-xs uppercase tracking-widest text-emerald-300">{{ __('Right') }}</p>
                    <p class="mt-1 max-w-20 truncate font-semibold">{{ $playerNames[2] ?? __('Waiting') }}</p>
                </div>
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center">
                    <p class="text-xs uppercase tracking-widest text-emerald-300">{{ __('You') }}</p>
                    <p class="mt-1 font-semibold">{{ auth()->user()->name }}</p>
                </div>

                <div
                    class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center gap-4 sm:gap-8">
                    <div class="text-center">
                        <div
                            class="flex h-28 w-20 items-center justify-center rounded-xl border-2 border-dashed border-emerald-500 bg-emerald-900/70 text-xs text-emerald-300 shadow-lg sm:h-36 sm:w-24">
                            {{ __('Deck') }}
                        </div>
                        <p class="mt-2 text-xs text-emerald-300">{{ __('44 cards') }}</p>
                    </div>
                    <div class="text-center">
                        <div
                            class="flex h-28 w-20 items-center justify-center rounded-xl border-2 border-white bg-white text-3xl font-bold text-rose-600 shadow-lg sm:h-36 sm:w-24">
                            ♥
                        </div>
                        <p class="mt-2 text-xs text-emerald-300">{{ __('Discard pile') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-default bg-neutral-primary p-4">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="font-semibold text-heading">{{ __('Your hand') }}</h3>
                    <span class="text-sm text-body">{{ __('8 cards') }}</span>
                </div>
                <div class="grid grid-cols-4 gap-2 sm:grid-cols-8">
                    @foreach ($hand as $card)
                        <img src="{{ asset("cards/{$card}.svg") }}" alt="{{ $cardLabels[$card] }}"
                            class="w-full rounded-lg shadow-sm transition-transform hover:-translate-y-1">
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Finished state --}}
        <section
            class="hidden rounded-2xl border border-default bg-neutral-primary-soft p-8 text-center shadow-xs sm:p-12"
            aria-label="{{ __('Finished game') }}">
            <span
                class="inline-flex items-center rounded-full bg-brand-softer px-3 py-1 text-xs font-semibold uppercase tracking-wide text-fg-brand">
                {{ __('Finished') }}
            </span>
            <h2 class="mt-5 text-4xl font-bold text-heading">{{ __('Alex won the game!') }}</h2>
            <p class="mt-3 text-lg text-body">{{ __('The game took 08:42 to complete.') }}</p>
            <div class="mx-auto mt-8 grid max-w-xl gap-3 sm:grid-cols-2">
                <div class="rounded-xl border border-default bg-neutral-primary px-5 py-4 text-left">
                    <p class="text-sm text-body">{{ __('Winner') }}</p>
                    <p class="mt-1 font-semibold text-heading">{{ __('Alex') }}</p>
                </div>
                <div class="rounded-xl border border-default bg-neutral-primary px-5 py-4 text-left">
                    <p class="text-sm text-body">{{ __('Winning melds') }}</p>
                    <p class="mt-1 font-semibold text-heading">{{ __('2 valid sets') }}</p>
                </div>
            </div>
        </section>
    </main>
</x-layouts.auth>
