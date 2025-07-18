<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filip's News Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @stack('styles') {{-- Allow other files to push CSS safely here --}}
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">News</a>
            <ul class="navbar-nav ms-auto d-flex flex-row gap-3">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->email }}</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    {{-- Script to remove autocomplete="off" from hidden CSRF token --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('input[type="hidden"][autocomplete="off"]').forEach(el => {
                el.removeAttribute('autocomplete');
            });
        });
    </script>
</body>
</html>
