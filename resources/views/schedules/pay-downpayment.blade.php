<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Downpayment - Super Carry Emission Testing Co</title>
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
<!-- Main Content -->
<main class="app-main">
    <div class="app-container py-12 md:py-16">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pay Downpayment</h1>
            <p class="text-gray-600 mt-1">Please complete your downpayment to confirm your booking</p>
        </div>
        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-10">
            <!-- GCash QR -->
            <div class="card flex flex-col items-center justify-center pb-2">
                <div class="mb-4 flex flex-col items-center w-full">
                    <img src="{{ asset('gcash-qr.png') }}" alt="GCash QR Code" class="w-full max-w-xs md:max-w-sm lg:max-w-md aspect-square object-contain rounded-xl shadow-lg border-4 border-blue-300 bg-white p-4 mb-4" style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                </div>
                <span class="text-xs text-blue-900/70 tracking-wide uppercase font-medium mb-1">Pay to: Super Carry Emission Testing Co GCash</span>
                <span class="text-lg font-bold text-indigo-700 mt-1 pb-1">Amount Due: <span class="text-blue-800">₱{{ number_format($schedule->downpayment_amount,2) }}</span></span>
                <span class="text-gray-700 text-sm mb-2 text-center">Scan QR code with GCash app to pay your downpayment, then use the form to upload your payment proof.</span>
                <span class="mt-3 text-blue-700 text-xs opacity-80 italic">Your booking is not confirmed until payment proof is submitted.</span>
            </div>
            <!-- Payment Form -->
            <div class="card">
                <form method="POST" action="{{ route('schedules.submitDownpayment', $schedule) }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <div class="form-group">
                        <label class="form-label font-semibold">Payment Method</label>
                        <input type="text" value="GCash" readonly class="form-input bg-gray-100 cursor-not-allowed border border-blue-200" name="payment_method">
                        <input type="hidden" name="payment_method" value="gcash">
                    </div>
                    <div class="form-group">
                        <label for="transaction_id" class="form-label font-semibold">GCash Transaction ID</label>
                        <input type="text" name="transaction_id" id="transaction_id" required class="form-input border-blue-200 bg-white" placeholder="Paste your GCash transaction ID here">
                        @error('transaction_id')
                            <p class="form-error text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="payment_proof" class="form-label font-semibold">Upload GCash Payment Screenshot</label>
                        <input type="file" name="payment_proof" id="payment_proof" required accept="image/*" class="form-input border-blue-200 bg-white">
                        @error('payment_proof')
                            <p class="form-error text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-full py-3 text-lg font-bold">Submit Downpayment</button>
                </form>
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
