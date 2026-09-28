<x-layouts.auth
    title="{{ __('Room :code', ['code' => $room->code]) }} - {{ config('app.name') }}"
>
    <main class="mx-auto max-w-5xl px-6 py-16">
        <div class="mb-8 text-center">
            <p class="mb-2 text-sm font-medium uppercase tracking-wide text-fg-brand">{{ __('Room') }}</p>
            <h1 class="text-4xl font-bold tracking-tight text-heading">{{ $room->code }}</h1>
            <p class="mt-3 text-gray-500 dark:text-gray-400">
                {{ __('Players: :count/4', ['count' => $room->players->count()]) }}
            </p>
        </div>

        <div class="mx-auto max-w-md bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
            <h2 class="mb-4 text-xl font-semibold text-heading">{{ __('Players') }}</h2>

            <ul class="space-y-3">
                @foreach ($room->players as $roomPlayer)
                    <li class="rounded-base bg-neutral-secondary-soft px-4 py-3 text-body">
                        {{ $roomPlayer->player->name }}
                    </li>
                @endforeach
            </ul>
        </div>
    </main>
</x-layouts.auth>
