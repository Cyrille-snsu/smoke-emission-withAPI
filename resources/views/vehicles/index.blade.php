<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Vehicles - Super Carry Emission Testing Co</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { darkMode: 'media' }
        </script>
    @endif
</head>
<body class="min-h-screen bg-blue-100" style="background-color:#e0f2fe;">
    <header class="app-header">
        <nav class="app-nav">
            <div class="flex items-center">
                <a href="#" onclick="window.history.back(); return false;" class="back-button-with-text flex items-center">
                    <span class="back-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="back-text ml-2">Back</span>
                </a>
                <div class="app-logo ml-4">
                    <a href="/" class="flex items-center gap-3">
                        <span class="app-logo-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M7 21a1 1 0 0 1-1-1v-1.382a4 4 0 0 1 1.172-2.829l6.647-6.647a2 2 0 1 1 2.828 2.829L10 18.618A4 4 0 0 1 7.171 20H7Z"/><path d="M15 3a4 4 0 0 1 4 4v2h-2V7a2 2 0 1 0-4 0v.586l-2 2V7a4 4 0 0 1 4-4Z"/></svg>
                        </span>
                        <span class="app-logo-text">Super Carry Emission Testing Co</span>
                    </a>
                </div>
            </div>
            <div class="app-nav-actions">
                <a href="{{ route('profile.show') }}" class="app-user-profile">
                    <span class="app-user-avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="app-user-name">{{ auth()->user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="app-logout-btn">Sign out</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="app-main">
        <div class="app-container">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Vehicles</h1>
                <p class="text-gray-600 mt-1">Add and manage your registered vehicles</p>
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

            <div class="max-w-4xl mx-auto space-y-8">
                <!-- Add Vehicle Form Card -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Add New Vehicle</h2>
                        <p class="card-subtitle">Register your vehicle for emission testing</p>
                    </div>
                    <div class="card-content">
                        <form method="POST" action="{{ route('vehicles.store') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Vehicle Details Row 1 -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Make</label>
                                    <input name="make" placeholder="e.g., Toyota, Honda, Ford" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Model</label>
                                    <input name="model" placeholder="e.g., Camry, Civic, F-150" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Year</label>
                                    <input name="year" type="number" placeholder="e.g., 2020" min="1900" max="2030" class="form-input" required />
                                </div>
                            </div>
                            
                            <!-- Vehicle Details Row 2 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Plate Number</label>
                                    <input name="plate_number" placeholder="e.g., ABC-123, XYZ-789" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Vehicle Type</label>
                                    <select name="vehicle_type" class="form-select" required>
                                        <option value="">Select vehicle type</option>
                                        <option value="motorcycle">🏍️ Motorcycle</option>
                                        <option value="tricycle">🛺 Tricycle</option>
                                        <option value="auto">🚗 Auto/Car</option>
                                        <option value="suv">🚙 SUV</option>
                                        <option value="truck">🚛 Truck</option>
                                        <option value="bus">🚌 Bus</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Vehicle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Vehicles List Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="card-title">Registered Vehicles</h2>
                                <p class="card-subtitle">Your vehicles available for scheduling</p>
                            </div>
                            <span class="badge badge-info">{{ $vehicles->count() }} vehicle(s)</span>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="space-y-3">
                            @forelse ($vehicles as $vehicle)
                                <div class="profile-vehicle-item">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-blue-100 rounded-xl flex items-center justify-center group-hover:from-emerald-200 group-hover:to-blue-200 transition-all duration-200">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900 text-lg">{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}</div>
                                                <div class="flex items-center space-x-3 text-sm text-slate-600">
                                                    <span class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <span>{{ $vehicle->plate_number }}</span>
                                                    </span>
                                                    <span class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="font-medium text-emerald-600">{{ $vehicle->vehicle_type_display }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" class="flex-shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" onclick="return confirm('Are you sure you want to remove this vehicle?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border-2 border-dashed border-gray-300">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">No vehicles registered yet</h4>
                                    <p class="text-gray-600 mb-4">Start by adding your first vehicle above to schedule emission tests</p>
                                    <div class="w-8 h-1 bg-blue-200 rounded-full mx-auto"></div>
                                </div>
                            @endforelse
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

