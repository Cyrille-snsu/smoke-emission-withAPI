<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Details - Super Carry Emission Testing Co</title>
    
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
                    <h1 class="text-3xl font-bold text-gray-900">Schedule Details</h1>
                    <p class="text-gray-600 mt-1">View your smoke emission test appointment</p>
                </div>
                <div class="flex items-center space-x-4">
                    @if($schedule->status === 'pending')
                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Schedule
                        </a>
                    @endif
                    @if($schedule->downpayment_status === 'pending')
                        <a href="{{ route('schedules.payDownpayment', $schedule) }}" class="btn btn-primary">
                            <i class="fas fa-credit-card mr-1"></i> Submit Payment Proof
                        </a>
                    @endif
                    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Schedules
                    </a>
                </div>
            </div>

            <!-- Schedule Details Card -->
            <div class="card">
                <!-- Status Header -->
                <div class="card-header bg-gradient-to-r
                    @if($schedule->status === 'pending') from-yellow-50 to-orange-50 border-yellow-200
                    @elseif($schedule->status === 'confirmed') from-green-50 to-emerald-50 border-green-200
                    @elseif($schedule->status === 'completed') from-blue-50 to-indigo-50 border-blue-200
                    @else from-red-50 to-pink-50 border-red-200
                    @endif">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="badge
                                @if($schedule->status === 'pending') badge-warning
                                @elseif($schedule->status === 'confirmed') badge-success
                                @elseif($schedule->status === 'completed') badge-success
                                @else badge-danger
                                @endif">
                                {{ ucfirst($schedule->status) }}
                            </span>
                            @if($schedule->status === 'pending' && $schedule->downpayment_status === 'pending')
                                <div class="text-xs text-amber-600 mt-1">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Awaiting admin confirmation
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-700">
                                @if($schedule->status === 'pending')
                                    Awaiting confirmation
                                @elseif($schedule->status === 'confirmed')
                                    Confirmed and ready
                                @elseif($schedule->status === 'completed')
                                    Test completed
                                @else
                                    Appointment cancelled
                                @endif
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Created {{ $schedule->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="card-content">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Test Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Test Type</label>
                                    <div class="mt-1">
                                        <span class="badge badge-info">{{ ucfirst($schedule->test_type) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Test Date</label>
                                    <div class="mt-1 text-gray-900">{{ $schedule->test_date->format('l, F d, Y') }}</div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Test Time</label>
                                    <div class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($schedule->test_time)->format('g:i A') }}</div>
                                </div>
                                @if($schedule->notes)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Additional Notes</label>
                                        <div class="mt-1 text-gray-900">{{ $schedule->notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                        <div class="font-semibold text-gray-900">{{ $schedule->vehicle->year }} {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }}</div>
                                        <div class="text-sm text-gray-600">Plate: {{ $schedule->vehicle->plate_number }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-4">Important Information</h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-blue-800">Please arrive 15 minutes before your scheduled time</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-blue-800">Bring your vehicle registration and insurance documents</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-blue-800">Ensure your vehicle is in good working condition</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-blue-800">Test typically takes 30-45 minutes to complete</span>
                            </li>
                            @if($schedule->status === 'pending')
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-blue-800">You will receive a confirmation email once your appointment is confirmed</span>
                                </li>
                            @endif
                        </ul>
                    </div>

                                         <!-- Payment Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Payment Details</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                                    <div class="text-2xl font-bold text-gray-900">₱{{ number_format($schedule->total_amount, 2) }}</div>
                             </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Downpayment (50%)</div>
                                    <div class="text-2xl font-bold text-blue-600">₱{{ number_format($schedule->downpayment_amount, 2) }}</div>
                             </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Remaining Balance</div>
                                    <div class="text-2xl font-bold text-gray-900">₱{{ number_format($schedule->getRemainingBalance(), 2) }}</div>
                             </div>
                         </div>
                         
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                    <div class="text-sm text-gray-600 mb-1">Payment Method</div>
                                    <div class="font-medium text-gray-900">{{ str_replace('_', ' ', $schedule->payment_method) }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Transaction ID</div>
                                    <div class="font-medium text-gray-900">{{ $schedule->transaction_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                            
                            @if($schedule->payment_proof)
                            <div class="mt-6">
                                <div class="text-sm text-gray-600 mb-2">Payment Proof</div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <img src="{{ asset('storage/' . $schedule->payment_proof) }}" 
                                         alt="Payment Proof" 
                                         class="payment-proof-image cursor-pointer"
                                         onclick="window.open(this.src, '_blank')">
                                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                                </div>
                            </div>
                            @endif
                             </div>
                             <div>
                                    <div class="text-sm text-gray-600 mb-1">Downpayment Status</div>
                                    <span class="badge
                                        @if($schedule->downpayment_status === 'paid') badge-success
                                        @elseif($schedule->downpayment_status === 'pending') badge-warning
                                        @else badge-danger
                                     @endif">
                                        {{ ucfirst($schedule->downpayment_status) }}
                                 </span>
                             </div>
                         </div>
                         
                         @if($schedule->downpayment_paid_at)
                                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="text-sm text-green-800">
                                        <strong>Downpayment received on:</strong> {{ $schedule->downpayment_paid_at->format('M d, Y g:i A') }}
                                 @if($schedule->transaction_id)
                                            <br><strong>Transaction ID:</strong> <span class="font-mono">{{ $schedule->transaction_id }}</span>
                                 @endif
                                    </div>
                             </div>
                         @endif
                         
                         @if($schedule->payment_notes)
                                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="text-sm text-yellow-800">{{ $schedule->payment_notes }}</div>
                             </div>
                         @endif
                        </div>
                     </div>

                     <!-- Action Buttons -->
                     @if($schedule->status === 'pending')
                        <div class="mt-8 flex flex-wrap gap-4">
                             <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this schedule?')">
                                 @csrf
                                 @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                     Cancel Appointment
                                 </button>
                             </form>
                             
                            <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                     Edit Schedule
                                 </a>
                         </div>
                     @endif
                 </div>
             </div>
         </div>
     </main>
 
     <!-- GCash Modal -->
    <div id="gcash-modal" class="modal-overlay hidden">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Pay Downpayment via GCash</h3>
                <button onclick="closeGcashModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-content">
                <div class="text-center mb-6">
                    <img src="/gcash-qr.png" alt="GCash QR Code" class="mx-auto mb-4 rounded-lg shadow-lg max-w-48">
                    <p class="text-gray-600">Scan the QR code using your GCash app and pay the downpayment amount. After payment, enter your GCash Transaction ID below to confirm.</p>
                </div>
                <div class="form-group">
                    <label for="transaction_id" class="form-label">GCash Transaction ID <span class="text-red-500">*</span></label>
                    <input type="text" id="transaction_id" class="form-input" placeholder="Enter your GCash Transaction ID">
                    <p id="transaction-id-error" class="form-error hidden">Transaction ID is required.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeGcashModal()" class="btn btn-secondary">Cancel</button>
                <button onclick="submitGcashPayment()" class="btn btn-primary">Submit Payment</button>
             </div>
         </div>
     </div>
 
     <script>
         const gcashModal = document.getElementById('gcash-modal');
         const openBtn = document.getElementById('open-gcash-modal');
         const form = document.getElementById('downpayment-form');
         const transactionIdInput = document.getElementById('transaction_id');
         const transactionIdError = document.getElementById('transaction-id-error');
 
         if (openBtn) {
             openBtn.addEventListener('click', function () {
                 gcashModal.classList.remove('hidden');
             });
         }
 
         function closeGcashModal() {
             gcashModal.classList.add('hidden');
         }
 
         function submitGcashPayment() {
             if (!transactionIdInput.value.trim()) {
                 transactionIdError.classList.remove('hidden');
                 transactionIdInput.focus();
                 return;
             }
             transactionIdError.classList.add('hidden');
             let hiddenInput = document.getElementById('hidden-transaction-id');
             if (!hiddenInput) {
                 hiddenInput = document.createElement('input');
                 hiddenInput.type = 'hidden';
                 hiddenInput.name = 'transaction_id';
                 hiddenInput.id = 'hidden-transaction-id';
                 form.appendChild(hiddenInput);
             }
             hiddenInput.value = transactionIdInput.value.trim();
             form.submit();
         }
     </script>

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
