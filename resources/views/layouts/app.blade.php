<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="antialiased bg-grey-light">
        <div class="bg-gray-100">
            <nav class="bg-white">
                <div class="container mx-auto">
                    <div class="flex justify-between items-center py-2">
                        <h1>
                            <a class="nav-brand" href="{{ url('/') }}">
                                <img src="/images/logo.svg" alt="Birboard">
                            </a>
                        </h1>

                        <div>
                            <div>{{ Auth::user()->name }}</div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="container mx-auto py-4">
                @yield('content')
            </main>
        </div>
    </body>
</html>
