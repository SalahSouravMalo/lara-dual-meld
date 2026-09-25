<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <nav class="bg-neutral-primary fixed w-full z-20 top-0 start-0 border-b border-default">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span
                    class="self-center text-xl text-heading font-semibold whitespace-nowrap">{{ config('app.name') }}</span>
            </a>
            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <button type="button"
                    class="flex text-sm bg-neutral-primary rounded-full md:me-0 focus:ring-4 focus:ring-neutral-tertiary cursor-pointer"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <span
                        class="flex items-center justify-center w-8 h-8 rounded-full bg-neutral-secondary text-heading font-medium"
                        aria-label="{{ auth()->user()->name }}">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44"
                    id="user-dropdown">
                    <div class="px-4 py-3 text-sm border-b border-default">
                        <span class="block text-heading font-medium">{{ auth()->user()->name }}</span>
                        <span class="block text-body truncate">{{ auth()->user()->email }}</span>
                    </div>
                    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="user-menu-button">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded cursor-pointer">{{ __('Sign out') }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-body rounded-base md:hidden hover:bg-neutral-secondary-soft hover:text-heading focus:outline-none focus:ring-2 focus:ring-neutral-tertiary"
                    aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M5 7h14M5 12h14M5 17h14" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <div class="mx-auto max-w-5xl px-6 mt-32">
        <div class="mb-10 text-center">
            <h1 class="mb-2 text-4xl font-bold tracking-tight text-heading">
                {!! __('Welcome to :app_name', ['app_name' => '<span class="text-fg-brand">' . config('app.name') . '</span>']) !!}
            </h1>
            <p class="text-lg text-gray-500 dark:text-gray-400">
                {{ __('Create a new room or join an existing one.') }}
            </p>
        </div>
    </div>

    <div class="bg-neutral-primary-soft block max-w-sm mx-auto p-6 border border-default rounded-base shadow-xs">
        <h5 class="mb-3 text-2xl font-semibold tracking-tight text-heading leading-8">{{ __('Create a Room') }}</h5>
        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Start a new game and invite other players to join. Each game supports up to four players, and any open spots will be filled by bots.') }}
        </p>
        <form action="{{ route('rooms.store') }}" method="post">
            @csrf

            <button type="submit"
                class="inline-flex items-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                {{ __('Create Room') }}
                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 12H5m14 0-4 4m4-4-4-4" />
                </svg>
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.2/dist/flowbite.min.js"></script>
</body>

</html>
