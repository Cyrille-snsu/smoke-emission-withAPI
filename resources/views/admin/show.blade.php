<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule #{{ $schedule->id }} - Admin - Super Carry Emission Testing Co</title>
    
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
                <a href="{{ route('admin.index') }}" class="app-login-btn">← Back to Admin</a>
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
                <h1 class="text-3xl font-bold text-gray-900">Schedule #{{ $schedule->id }}</h1>
                <p class="text-gray-600 mt-1">View and manage schedule details</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Customer & Vehicle</h2>
                    </div>
                    <div class="card-content">
                        <div class="space-y-6">
                            <div>
                                <div class="text-sm font-medium text-gray-500 mb-1">Customer</div>
                                <div class="font-semibold text-gray-900">{{ $schedule->user->name }}</div>
                                <div class="text-sm text-gray-600">{{ $schedule->user->email }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 mb-1">Vehicle</div>
                                <div class="font-semibold text-gray-900">{{ $schedule->vehicle->year }} {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }}</div>
                                <div class="text-sm text-gray-600">Plate: {{ $schedule->vehicle->plate_number }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Payment Details</h2>
                    </div>
                    <div class="card-content">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                                <div class="font-bold text-lg">₱{{ number_format($schedule->total_amount, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Downpayment (50%)</div>
                                <div class="font-bold text-lg">₱{{ number_format($schedule->downpayment_amount, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Status</div>
                                <span class="badge {{ $schedule->downpayment_status==='paid' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($schedule->downpayment_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 space-y-2">
                            <div>Method: <span class="font-medium">{{ strtoupper($schedule->payment_method) }}</span></div>
                            @if($schedule->downpayment_paid_at)
                                <div>Paid on: <span class="font-medium">{{ $schedule->downpayment_paid_at->format('M d, Y g:i A') }}</span></div>
                            @endif
                            <div>Transaction ID: <span class="font-mono font-semibold">{{ $schedule->transaction_id ?? '—' }}</span></div>
                            @if($schedule->payment_notes)
                                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded">{{ $schedule->payment_notes }}</div>
                            @endif
                        </div>
                        
                        @if($schedule->payment_proof)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Proof</h3>
                            <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                <img src="{{ asset('storage/' . $schedule->payment_proof) }}" 
                                     alt="Payment Proof" 
                                     class="payment-proof-image cursor-pointer max-w-full h-auto"
                                     onclick="window.open(this.src, '_blank')">
                                <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                @if($schedule->status === 'pending' && $schedule->downpayment_status === 'pending')
                    <form method="POST" action="{{ route('admin.schedules.confirm', $schedule) }}">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Confirm this payment and approve the schedule?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Confirm Payment & Approve
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" onclick="showRejectModal()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reject Payment
                    </button>
                @elseif($schedule->status === 'confirmed')
                    <div class="text-green-600 font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Payment Confirmed & Schedule Approved
                    </div>
                @elseif($schedule->status === 'cancelled')
                    <div class="text-red-600 font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Payment Rejected & Schedule Cancelled
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Reject Payment</h3>
                <button onclick="closeRejectModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.schedules.reject', $schedule) }}" id="rejectionForm">
                    @csrf
                    <div class="form-group">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" required rows="4" 
                                  class="form-textarea w-full p-2 border rounded" placeholder="Please provide a reason for rejecting this payment..."></textarea>
                        <p class="form-help text-sm text-gray-500 mt-1">This reason will be shown to the customer.</p>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="button" onclick="submitRejection()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Reject Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectionForm').reset();
        }

        function submitRejection() {
            const reason = document.getElementById('rejection_reason').value.trim();
            if (!reason) {
                alert('Please provide a reason for rejection.');
                return;
            }
            if (confirm('Are you sure you want to reject this payment? This action cannot be undone.')) {
                document.getElementById('rejectionForm').submit();
            }
        }
    </script>

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
