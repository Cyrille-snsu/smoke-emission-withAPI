<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedules - Super Carry Emission Testing Co</title>
    
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
    <!-- Header -->
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
                <a href="{{ route('profile.show') }}" class="app-user-profile">
                    <span class="app-user-avatar">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="app-user-name">{{ auth()->user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="app-logout-btn">Sign out</button>
                </form>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="app-main">
        <div class="app-container">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Schedules</h1>
                    <p class="text-gray-600 mt-1">Manage your smoke emission test appointments</p>
                </div>
                <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Schedule
                </a>
            </div>

            <!-- Info Card -->
            <div class="card mb-8">
                <div class="card-content">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Time Slot Availability</h3>
                            <p class="text-gray-600">Smoke emission tests take 2 hours each. Multiple tests can be scheduled on the same day at different times. The system automatically prevents overlapping time slots to ensure smooth scheduling.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Schedules List -->
            @if($schedules->count() > 0)
                <div class="table-container">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Test Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $schedule->vehicle->year }} {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }}</div>
                                                <div class="text-sm text-gray-500">{{ $schedule->vehicle->plate_number }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-900">{{ $schedule->test_date->format('M d, Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="text-gray-900">{{ \Carbon\Carbon::parse($schedule->test_time)->format('g:i A') }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst($schedule->test_type) }}
                                            </span>
                                        </td>
                                        <td>
                                             <span class="badge
                                                 @if($schedule->status === 'pending') badge-warning
                                                 @elseif($schedule->status === 'confirmed') badge-success
                                                 @elseif($schedule->status === 'completed') badge-success
                                                 @else badge-danger
                                                 @endif">
                                                 {{ ucfirst($schedule->status) }}
                                             </span>
                                         </td>
                                         <td>
                                             <div>
                                                 <div class="font-medium text-gray-900">₱{{ number_format($schedule->total_amount, 2) }}</div>
                                                 <div class="text-sm">
                                                     <span class="badge
                                                         @if($schedule->downpayment_status === 'paid') badge-success
                                                         @elseif($schedule->downpayment_status === 'pending') badge-warning
                                                         @else badge-danger
                                                         @endif">
                                                         {{ ucfirst($schedule->downpayment_status) }}
                                                     </span>
                                                     <span class="text-gray-500 ml-2">
                                                         ₱{{ number_format($schedule->downpayment_amount, 2) }}
                                                     </span>
                                                 </div>
                                             </div>
                                         </td>
                                         <td>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-secondary">
                                                    View
                                                </a>
                                                @if($schedule->status === 'pending')
                                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-secondary">
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this schedule?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No schedules yet</h3>
                    <p class="text-gray-600 mb-6">You haven't scheduled any smoke emission tests yet.</p>
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Schedule Your First Test
                    </a>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Quick Schedule</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Book a test in just a few clicks</p>
                        <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm">
                            Schedule Now →
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Test Status</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Track your test progress</p>
                        <span class="text-gray-400 text-sm">View schedules above</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Need Help?</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Get assistance with scheduling</p>
                        <span class="text-gray-400 text-sm">Contact support</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
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
