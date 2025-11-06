<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Log in - {{ config('app.name', 'Super Carry Emission Testing Co') }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-blue-100" style="background-color:#e0f2fe;">
        <header class="app-header">
            <nav class="app-nav">
                <div class="app-logo">
                    <a href="/" class="flex items-center gap-3">
                        <span class="app-logo-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M7 21a1 1 0 0 1-1-1v-1.382a4 4 0 0 1 1.172-2.829l6.647-6.647a2 2 0 1 1 2.828 2.829L10 18.618A4 4 0 0 1 7.171 20H7Z"/><path d="M15 3a4 4 0 0 1 4 4v2h-2V7a2 2 0 1 0-4 0v.586l-2 2V7a4 4 0 0 1 4-4Z"/></svg>
                        </span>
                        <span class="app-logo-text">Super Carry Emission Testing Co</span>
                    </a>
                </div>
                <div class="app-nav-actions">
                    @auth
                        <a href="/profile" class="app-user-profile">
                            <span class="app-user-avatar">
                                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="app-user-name">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="app-logout-btn">Sign out</button>
                        </form>
                    @else
                        <a href="/register" class="app-signup-btn">Sign up</a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="app-main">
            <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
                <div class="form-container">
                    <div class="form">
                        <h1 class="form-title">Welcome Back</h1>
                        <p class="form-subtitle">Log in to manage your appointments and view your digital results</p>

                        <form method="POST" action="/login">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input" required />
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="password">Password</label>
                                <input id="password" name="password" type="password" class="form-input" required />
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" value="1" class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                    <span class="text-sm text-gray-600">Remember me</span>
                                </label>
                                <a href="#" class="form-link text-sm">Forgot password?</a>
                            </div>

                            <div class="form-actions">
                                <a href="/" class="form-link">← Back to homepage</a>
                                <button type="submit" class="form-submit">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <footer class="app-footer">
            <div class="app-footer-content">
                <span>&copy; {{ date('Y') }} Super Carry Emission Testing Co. All rights reserved.</span>
                <div class="app-footer-links">
                    <a href="#" class="app-footer-link">Privacy</a>
                    <a href="#" class="app-footer-link">Terms</a>
                    <a href="#" class="app-footer-link">Contact</a>
                </div>
            </div>
        </footer>
    </body>
</html>


