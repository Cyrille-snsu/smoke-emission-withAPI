<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>My Profile - {{ config('app.name', 'Super Carry Emission Testing Co') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        
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
                    <a href="{{ route('schedules.index') }}" class="app-login-btn">My Schedules</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-logout-btn">Sign out</button>
                    </form>
                </div>
            </nav>
        </header>

        <main class="app-main">
            <div class="app-container">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                    <p class="text-gray-600 mt-1">Manage your account information and vehicles</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Profile Information Card -->
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Profile Information</h2>
                                <p class="card-subtitle">Update your personal details</p>
                            </div>
                            <div class="card-content">
                                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label" for="name">Full Name</label>
                                        <input 
                                            id="name" 
                                            name="name" 
                                            type="text" 
                                            value="{{ old('name', $user->name) }}" 
                                            class="form-input" 
                                            required 
                                        />
                                        @error('name')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="email">Email Address</label>
                                        <input 
                                            id="email" 
                                            name="email" 
                                            type="email" 
                                            value="{{ old('email', $user->email) }}" 
                                            class="form-input" 
                                            required 
                                        />
                                        @error('email')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="pt-2">
                                        <button type="submit" class="btn btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Quick Actions</h2>
                                <p class="card-subtitle">Access your schedules, vehicles, and book tests</p>
                            </div>
                            <div class="card-content">
                                <div class="space-y-4">
                                    <a href="{{ route('schedules.create') }}" class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-green-900">Schedule Test</div>
                                            <div class="text-sm text-green-700">Book a new emission test</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('schedules.index') }}" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-blue-900">View Schedules</div>
                                            <div class="text-sm text-blue-700">Check your test appointments</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('vehicles.index') }}" class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-purple-900">Manage Vehicles</div>
                                            <div class="text-sm text-purple-700">Add and manage your vehicles</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="mt-auto py-6 border-t border-gray-300/50 bg-transparent">
            <div class="app-container">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-700 space-y-2 md:space-y-0">
                    <span>&copy; {{ date('Y') }} Super Carry Emission Testing Co. All rights reserved.</span>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors">Privacy</a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors">Terms</a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>


