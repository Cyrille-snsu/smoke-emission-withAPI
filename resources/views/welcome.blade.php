<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    darkMode: 'media'
                }
            </script>
        @endif
    </head>
    <body class="min-h-screen bg-blue-100" style="background-color:#e0f2fe;">
        <!-- Header -->
        <header class="app-header">
            <nav class="app-nav">
                <div class="app-logo">
                    <span class="app-logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M7 21a1 1 0 0 1-1-1v-1.382a4 4 0 0 1 1.172-2.829l6.647-6.647a2 2 0 1 1 2.828 2.829L10 18.618A4 4 0 0 1 7.171 20H7Z"/>
                            <path d="M15 3a4 4 0 0 1 4 4v2h-2V7a2 2 0 1 0-4 0v.586l-2 2V7a4 4 0 0 1 4-4Z"/>
                        </svg>
                    </span>
                    <span class="app-logo-text">Laravel</span>
                </div>
                <div class="app-nav-actions">
            @if (Route::has('login'))
                    @auth
                            <a href="{{ url('/dashboard') }}" class="app-login-btn">Dashboard</a>
                    @else
                            <a href="{{ route('login') }}" class="app-login-btn">Log in</a>
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="app-signup-btn">Register</a>
                        @endif
                    @endauth
                    @endif
                </div>
                </nav>
        </header>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-container">
                <!-- Hero Section -->
                <section class="hero-section animate-fade-in">
                    <div class="max-w-4xl mx-auto">
                        <h1 class="hero-title gradient-text">
                            Welcome to Super Carry Emission Testing Co
                        </h1>
                        <p class="hero-subtitle">
                            Professional smoke emission testing services for all vehicle types. Book your test online and get certified quickly and efficiently.
                        </p>
                        <div class="hero-cta">
                            <a href="{{ route('login') }}" class="hero-btn hero-btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Get Started
                            </a>
                            <a href="{{ route('register') }}" class="hero-btn hero-btn-secondary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Create Account
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Features Grid -->
                <section class="mb-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="card animate-fade-in" style="animation-delay: 0.1s">
                            <div class="card-content">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Lightning Fast</h3>
                                <p class="text-gray-600">Laravel provides a clean, elegant syntax that makes web development a joy. Build applications faster with less code.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="card animate-fade-in" style="animation-delay: 0.2s">
                            <div class="card-content">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Secure by Default</h3>
                                <p class="text-gray-600">Built-in security features protect your application from common vulnerabilities like SQL injection and XSS.</p>
                            </div>
                </div>

                        <!-- Feature 3 -->
                        <div class="card animate-fade-in" style="animation-delay: 0.3s">
                            <div class="card-content">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Developer Friendly</h3>
                                <p class="text-gray-600">Comprehensive documentation, elegant syntax, and a vibrant community make Laravel a joy to work with.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="app-footer-content">
                <span>&copy; {{ date('Y') }} Laravel. All rights reserved.</span>
                <div class="app-footer-links">
                    <a href="https://laravel.com/docs" class="app-footer-link">Documentation</a>
                    <a href="https://laracasts.com" class="app-footer-link">Laracasts</a>
                    <a href="https://laravel-news.com" class="app-footer-link">News</a>
                    <a href="https://github.com/laravel/laravel" class="app-footer-link">GitHub</a>
                </div>
        </div>
        </footer>
    </body>
</html>
