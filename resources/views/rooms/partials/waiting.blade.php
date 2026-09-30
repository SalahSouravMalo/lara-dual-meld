<section class="rounded-2xl border border-default bg-neutral-primary-soft p-6 shadow-xs sm:p-10"
    aria-label="{{ __('Waiting room') }}">
    <div class="mx-auto max-w-2xl text-center">
        <span
            class="inline-flex items-center rounded-full bg-brand-softer px-3 py-1 text-xs font-semibold uppercase tracking-wide text-fg-brand">
            {{ __('Waiting') }}
        </span>
        <h2 class="mt-5 text-3xl font-bold text-heading">{{ __('The game starts in 86 seconds') }}</h2>
        <p class="mt-3 text-body">
            {{ __('Invite everyone in. The table will open when the countdown reaches zero.') }}</p>
    </div>

    <div class="mx-auto mt-10 max-w-xl">
        {{ PHP_VERSION }}
        <div class="mb-4 flex items-center justify-between">
            2 <h3 class="text-lg font-semibold text-heading">{{ __('Players in the room') }}</h3>
            <span class="text-sm text-body">{{ __(':count of 4 joined', ['count' => $players->count()]) }}</span>
        </div>
        <ul class="grid gap-3 sm:grid-cols-2">
            @forelse ($players as $player)
                <li
                    class="flex items-center gap-3 rounded-xl border border-default bg-neutral-primary px-4 py-3 text-body">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-neutral-secondary text-sm font-semibold text-heading">
                        {{ str($player->name)->substr(0, 1)->upper() }}
                    </span>
                    <span class="font-medium">{{ $player->name }}</span>
                </li>
            @empty
                <li
                    class="rounded-xl border border-dashed border-default px-4 py-6 text-center text-body sm:col-span-2">
                    {{ __('Waiting for players to join...') }}
                </li>
            @endforelse
        </ul>
    </div>
</section>
