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
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:min-h-screen">
        <div
            class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-6 sm:p-8">
                <div class="space-y-3">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {!! __('Welcome to :app_name', ['app_name' => config('app.name', 'Laravel')]) !!}
                    </h1>

                    <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {!! __(
                            '<strong>:app_name</strong> is a real-time multiplayer card game built as a portfolio project to demonstrate production-ready application architecture.',
                            ['app_name' => config('app.name', 'Laravel')],
                        ) !!}
                    </p>

                    <p class="text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {!! __(
                            'Sign in with your <strong>Google</strong> account or choose one of the <strong>demo accounts</strong> to start playing. Only the minimum information required for authentication is stored, and your personal information is never shared with third parties.',
                            ['app_name' => config('app.name', 'Laravel')],
                        ) !!}
                    </p>
                </div>

                @session('error')
                    <div class="error-alert flex sm:items-center p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft"
                        role="alert">
                        <div class="text-sm">{{ session('error') }}</div>
                        <button type="button"
                            class="ms-auto -mx-1.5 -my-1.5 bg-danger-soft text-fg-danger-strong rounded focus:ring-2 focus:ring-danger-medium p-1.5 hover:bg-danger-medium inline-flex items-center justify-center h-8 w-8 shrink-0"
                            data-dismiss-target=".error-alert" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <x-icons.close class="w-4 h-4" />
                        </button>
                    </div>
                @endsession

                <div class="space-y-3">
                    <a href="{{ route('auth.google.redirect') }}" class="block">
                        <button type="button"
                            class="text-white bg-[#4285F4] hover:bg-[#4285F4]/90 focus:ring-4 focus:outline-none focus:ring-[#4285F4]/50 box-border border border-transparent font-medium leading-5 rounded-base text-sm px-4 py-2.5 text-center inline-flex justify-center items-center dark:focus:ring-[#4285F4]/55 w-full cursor-pointer">
                            <x-icons.google class="w-4 h-4 me-1.5" />
                            {{ __('Sign in with Google') }}
                        </button>
                    </a>

                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400 my-4">
                        <span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span>
                        <span>{{ __('or') }}</span>
                        <span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span>
                    </div>

                    <form action="{{ route('guest.login') }}" method="POST" class="block">
                        @csrf
                        <button type="submit"
                            class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 box-border border border-gray-300 font-medium leading-5 rounded-base text-sm px-4 py-2.5 text-center inline-flex justify-center items-center dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-700 w-full cursor-pointer">
                            {{ __('Continue as Guest') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.2/dist/flowbite.min.js"></script>
</body>

</html>
