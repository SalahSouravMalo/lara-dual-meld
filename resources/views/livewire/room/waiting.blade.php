<section class="rounded-2xl border border-default bg-neutral-primary-soft p-6 shadow-xs sm:p-10" x-data="{
    remaining: 0,
    timer: null,
    startsAt: @js($room->starts_at->toIso8601String()),
    init() {
        this.updateCountdown();

        if (this.remaining > 0) {
            this.timer = setInterval(() => this.updateCountdown(), 1000);
        }
    },
    updateCountdown() {
        this.remaining = Math.max(
            0,
            Math.ceil((new Date(this.startsAt).getTime() - Date.now()) / 1000),
        );

        if (this.remaining === 0 && this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },
    destroy() {
        if (this.timer) {
            clearInterval(this.timer);
        }
    },
}"
    x-init="init()" aria-label="{{ __('Waiting room') }}">
    <div class="mx-auto max-w-2xl text-center">
        <span
            class="inline-flex items-center rounded-full bg-brand-softer px-3 py-1 text-xs font-semibold uppercase tracking-wide text-fg-brand">
            {{ __('Waiting') }}
        </span>
        <h2 class="mt-5 text-3xl font-bold text-heading">
            {{ __('The game starts in') }}
            <span class="tabular-nums" x-text="remaining"></span>
            {{ __('seconds') }}
        </h2>
        <p class="mt-3 text-body">
            {{ __('Invite everyone in. The table will open when the countdown reaches zero.') }}
        </p>
    </div>

    <div class="mx-auto mt-10 max-w-xl">
        <div class="mb-4 flex items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-heading">{{ __('Players in the room') }}</h3>
            <span class="text-sm text-body">
                {{ __(':count of 4 joined', ['count' => count($state['players'])]) }}
            </span>
        </div>

        <ul class="grid gap-3 sm:grid-cols-2">
            @forelse ($state['players'] as $player)
                <li
                    class="flex items-center gap-3 rounded-xl border border-default bg-neutral-primary px-4 py-3 text-body">
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-neutral-secondary text-sm font-semibold text-heading"
                        aria-hidden="true">
                        {{ str($player['name'])->substr(0, 1)->upper() }}
                    </span>
                    <span class="truncate font-medium text-heading">{{ $player['name'] }}</span>
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
