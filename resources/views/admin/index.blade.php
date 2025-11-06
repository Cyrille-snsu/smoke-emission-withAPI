<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Super Carry Emission Testing Co</title>
    
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
                    <span class="app-logo-text">Admin Panel</span>
                </a>
            </div>
            <div class="app-nav-actions">
                <a href="/" class="app-login-btn">← Back to site</a>
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
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 mt-1">Manage schedules and monitor system activity</p>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card">
                    <div class="card-content text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $counts['pending'] }}</div>
                        <div class="text-sm font-medium text-gray-600">Pending</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $counts['confirmed'] }}</div>
                        <div class="text-sm font-medium text-gray-600">Confirmed</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $counts['completed'] }}</div>
                        <div class="text-sm font-medium text-gray-600">Completed</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content text-center">
                        <div class="text-3xl font-bold text-green-700 mb-2">{{ $counts['cancelled'] }}</div>
                        <div class="text-sm font-medium text-gray-600">Cancelled</div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="card mb-8">
                <div class="card-header">
                    <h2 class="card-title">Schedules Overview</h2>
                    <p class="text-sm text-gray-500">Distribution of schedules by status</p>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="schedulesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Schedules</h2>
                    <form method="GET" class="flex items-center space-x-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
                <div class="card-content p-0">
                <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Customer</th>
                                    <th>Vehicle</th>
                                    <th>Amount</th>
                                    <th>Downpayment</th>
                                    <th>Txn ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $s)
                                    <tr class="{{ $s->downpayment_status === 'pending' ? 'bg-yellow-50' : '' }}">
                                        <td>
                                            <div class="font-medium">{{ $s->test_date->format('Y-m-d') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($s->test_time)->format('g:i A') }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium">{{ $s->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $s->user->email }}</div>
                                        </td>
                                        <td>
                                            <div class="font-medium">{{ $s->vehicle->year }} {{ $s->vehicle->make }} {{ $s->vehicle->model }}</div>
                                            <div class="text-sm text-gray-500">{{ $s->vehicle->plate_number }}</div>
                                        </td>
                                        <td class="font-semibold">₱{{ number_format($s->total_amount, 2) }}</td>
                                        <td>
                                            <div class="font-medium">₱{{ number_format($s->downpayment_amount, 2) }}</div>
                                            <span class="badge {{ $s->downpayment_status==='paid' ? 'badge-success' : 'badge-warning' }}">
                                                {{ ucfirst($s->downpayment_status) }}
                                            </span>
                                        </td>
                                        <td class="font-mono text-sm">{{ $s->transaction_id ? $s->transaction_id : '—' }}</td>
                                        <td>
                                            <span class="badge
                                                @if($s->status === 'pending') badge-warning
                                                @elseif($s->status === 'confirmed') badge-info
                                                @elseif($s->status === 'completed') badge-success
                                                @else badge-danger
                                                @endif">
                                                {{ ucfirst($s->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.schedules.show', $s) }}" class="btn btn-sm btn-secondary">View</a>
                                                <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}" onsubmit="return confirm('Delete this schedule? This cannot be undone.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                            </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $schedules->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </main>

    <!-- Add Chart.js with explicit version -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        console.log('Script loaded, initializing chart...');
        
        function initializeChart() {
            const ctx = document.getElementById('schedulesChart');
            if (!ctx) {
                console.error('Could not find chart canvas element');
                return;
            }
            
            console.log('Canvas element found, getting context...');
            
            // Use actual data from the server instead of counting DOM elements
            const statusCounts = {
                pending: {{ $counts['pending'] ?? 0 }},
                confirmed: {{ $counts['confirmed'] ?? 0 }},
                completed: {{ $counts['completed'] ?? 0 }},
                cancelled: {{ $counts['cancelled'] ?? 0 }}
            };
            
            console.log('Status counts:', statusCounts);
            
            try {
                const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
                    datasets: [{
                        label: 'Number of Schedules',
                        data: [
                            statusCounts.pending,
                            statusCounts.confirmed,
                            statusCounts.completed,
                            statusCounts.cancelled
                        ],
                        backgroundColor: [
                            'rgba(187, 247, 208, 0.7)',  // green-100
                            'rgba(134, 239, 172, 0.7)',  // green-200
                            'rgba(74, 222, 128, 0.7)',   // green-300
                            'rgba(34, 197, 94, 0.7)'     // green-400
                        ],
                        borderColor: [
                            'rgb(134, 239, 172)',  // green-200
                            'rgb(74, 222, 128)',   // green-300
                            'rgb(34, 197, 94)',    // green-400
                            'rgb(22, 163, 74)'     // green-500
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            console.log('Chart initialized successfully');
        } catch (error) {
            console.error('Error initializing chart:', error);
        }
    }
    
    // Initialize chart when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeChart);
    } else {
        initializeChart();
    }
    </script>
    
    <style>
        #schedulesChart {
            width: 100% !important;
            height: 100% !important;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>

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
