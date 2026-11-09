<x-layouts.auth title="{{ __('Room :code', ['code' => $room->code]) }} - {{ config('app.name') }}"
    bodyClass="bg-gray-50 dark:bg-gray-950">
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Waiting state --}}
        <livewire:room :room="$room" />
    </main>
</x-layouts.auth>
