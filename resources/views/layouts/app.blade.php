<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Guidly — Des guides immersifs')</title>
    <meta name="description" content="@yield('description', 'Des guides immersifs pour accueillir, former et ne plus jamais répéter.')">
    <meta property="og:title" content="@yield('og_title', 'Guidly')">
    <meta property="og:description" content="@yield('og_description', 'Des guides immersifs pour accueillir, former et ne plus jamais répéter.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo-icone-og.png'))">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-icone.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col font-body bg-bg text-ink antialiased">
    @hasSection('custom_header')
        @yield('custom_header')
    @else
        <header class="w-full border-b border-bg-alt/60 bg-white/80 backdrop-blur-lg sticky top-0 z-40">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 sm:h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center shrink-0">
                    <img src="{{ asset('images/logo-horizontal.png') }}" alt="Guidly" class="h-8 sm:h-10 mix-blend-multiply">
                </a>
                <nav class="flex items-center gap-3 sm:gap-6">
                    @if(request()->routeIs('home'))
                        <a href="#comment-ca-marche" class="hidden md:inline-flex text-sm font-medium text-ink-alt hover:text-ink transition-colors">Comment ça marche</a>
                    @endif
                    <a href="{{ route('my-guides') }}" class="text-sm font-medium text-ink-alt hover:text-ink transition-colors whitespace-nowrap">Mes guides</a>
                    <a href="{{ route('guide.create') }}" class="inline-flex items-center h-9 px-4 sm:px-5 bg-accent text-white text-sm font-display font-semibold rounded-xl hover:bg-accent-hover transition-all duration-200 shadow-sm whitespace-nowrap">
                        <span class="hidden sm:inline">Créer un guide</span>
                        <span class="sm:hidden flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Créer
                        </span>
                    </a>
                </nav>
            </div>
        </header>
    @endif

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <footer class="w-full border-t border-bg-alt bg-white">
        <div class="max-w-5xl mx-auto px-6 py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo-horizontal.png') }}" alt="Guidly" class="h-8 mix-blend-multiply opacity-60">
                    </a>
                </div>
                <nav class="flex items-center gap-6 text-sm text-ink-alt">
                    <a href="{{ route('guide.create') }}" class="hover:text-ink transition-colors">Créer un guide</a>
                    <a href="{{ route('my-guides') }}" class="hover:text-ink transition-colors">Mes guides</a>
                </nav>
            </div>
            <div class="mt-8 pt-6 border-t border-bg-alt text-center text-xs text-ink-alt">
                &copy; {{ date('Y') }} Guidly — Des guides immersifs pour accueillir, former et ne plus jamais répéter.
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
