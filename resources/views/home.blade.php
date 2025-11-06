<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Online Smoke Scheduling') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

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
        <header class="app-header">
            <nav class="app-nav">
                <div class="app-logo">
                    <span class="app-logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M7 21a1 1 0 0 1-1-1v-1.382a4 4 0 0 1 1.172-2.829l6.647-6.647a2 2 0 1 1 2.828 2.829L10 18.618A4 4 0 0 1 7.171 20H7Z"/><path d="M15 3a4 4 0 0 1 4 4v2h-2V7a2 2 0 1 0-4 0v.586l-2 2V7a4 4 0 0 1 4-4Z"/></svg>
                    </span>
                    <span class="app-logo-text">Super Carry Emission Testing Co</span>
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
                        <a href="/login" class="app-login-btn">Log in</a>
                        <a href="/register" class="app-signup-btn">Sign up</a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="app-main">
            <div class="app-container">
                <!-- Hero Section -->
                <section class="hero-section animate-fade-in">
                    <div class="max-w-4xl mx-auto">
                        <h1 class="hero-title gradient-text">
                            Book Your Emission Test Now
                        </h1>
                        <p class="hero-subtitle">
                            No more waiting in long queues at emission testing centers. With our online platform, you can schedule your smoke emission test in just a few clicks, saving you valuable time. Get in and out quickly, with results delivered digitally for your convenience.
                        </p>
                        <div class="hero-cta flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                                <a  href="{{ route('vehicles.index') }}" class="hero-btn hero-btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Register Vehicle
                                </a>

                                <a href="/schedules/create" class="hero-btn hero-btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create Schedule
                                </a>

                                <a href="{{ route('schedules.index') }}" class="hero-btn hero-btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" ></path>
                                    </svg>
                                    View Schedules
                                </a>

                    @else
                                <a href="/register" class="hero-btn hero-btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Get Started
                                </a>
                    @endauth
                                <button onclick="openHelpModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-lg shadow-lg hover:from-orange-600 hover:to-red-600 transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Need Help To Book?
                                </button>
                        </div>
                </div>
            </section>

            <!-- Help Modal -->
            <div id="helpModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" onclick="closeHelpModal()"></div>

                <!-- Modal container -->
                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                    <!-- Modal panel -->
                    <div class="relative bg-white rounded-2xl shadow-xl transform transition-all w-full max-w-5xl max-h-[90vh] overflow-hidden">
                        <div class="bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-900" id="modal-title">Simple Step-by-Step Guide</h3>
                            <button onclick="closeHelpModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="bg-white px-6 py-6 overflow-y-auto max-h-[70vh]">
                            <p class="text-gray-600 mb-6 text-lg">Follow these easy steps to book your emission test</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Step 1 -->
                                <div class="flex gap-4 p-6 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">1</div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Create Your Account</h3>
                                        <p class="text-gray-700">Click <strong>"Sign up"</strong> button at the top right. Fill in your name, email, and create a password. Click <strong>"Register"</strong> when done.</p>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="flex gap-4 p-6 bg-green-50 rounded-xl border-l-4 border-green-500">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-xl">2</div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Add Your Vehicle</h3>
                                        <p class="text-gray-700">Go to your <strong>"Profile"</strong> page and click <strong>"Manage Vehicles"</strong>. Fill in your vehicle details (make, model, year, plate number) and click <strong>"Add Vehicle"</strong>.</p>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="flex gap-4 p-6 bg-purple-50 rounded-xl border-l-4 border-purple-500">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xl">3</div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Schedule Your Test</h3>
                                        <p class="text-gray-700">Click <strong>"Schedule Test"</strong> button. Select your vehicle, choose a date and time, pick the test type, and enter your mobile number. Click <strong>"Create Schedule"</strong>.</p>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div class="flex gap-4 p-6 bg-orange-50 rounded-xl border-l-4 border-orange-500">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl">4</div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Pay Downpayment</h3>
                                        <p class="text-gray-700">After creating your schedule, you'll see a QR code. Open your <strong>GCash app</strong>, scan the QR code, and pay 50% of the total amount. Enter the transaction ID and upload the payment screenshot.</p>
                                    </div>
                                </div>

                                <!-- Step 5 -->
                                <div class="flex gap-4 p-6 bg-indigo-50 rounded-xl border-l-4 border-indigo-500">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xl">5</div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Wait for Confirmation</h3>
                                        <p class="text-gray-700">Our staff will review your payment. Once confirmed, you'll receive a notification. You'll also get a call or message when your test time is coming up.</p>
                                    </div>
                                </div>

                                <!-- Step 6 -->
                                <div class="flex gap-4 p-6 bg-pink-50 rounded-xl border-l-4 border-pink-500">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">6</div>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">Arrive On Time</h3>
                                        <p class="text-gray-700">Come to our testing center 15 minutes before your scheduled time. Bring your vehicle registration documents. Pay the remaining balance at the counter. Our staff will assist you!</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 p-6 bg-yellow-50 rounded-xl border-2 border-yellow-300">
                                <div class="flex items-start gap-4">
                                    <svg class="w-8 h-8 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-yellow-900 text-lg mb-2">💡 Need More Help?</h4>
                                        <p class="text-yellow-800">If you're still having trouble, don't worry! Call us at <strong>(+63) 9615879739 CYRILLE DELA CRUZ</strong> or visit our office and our friendly staff will help you book in person.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end">
                            <button onclick="closeHelpModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Features Section -->
                <section class="mb-16">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Our Service?</h2>
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">Experience the future of emission testing with our modern, efficient platform.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="card animate-fade-in" style="animation-delay: 0.1s">
                            <div class="card-content">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Convenient Online Booking</h3>
                                <p class="text-gray-600">Our easy-to-use online booking system allows you to pick a date and time that fits your schedule. Whether you're at home, at work, or on the go, you can secure your smoke emission test without any hassle.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="card animate-fade-in" style="animation-delay: 0.2s">
                            <div class="card-content">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Eco-Friendly Compliance</h3>
                                <p class="text-gray-600">Help keep the environment clean and your vehicle legally compliant. Our smoke emission testing ensures that your vehicle meets government regulations, reducing harmful emissions and contributing to a healthier community.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="card animate-fade-in" style="animation-delay: 0.3s">
                            <div class="card-content">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Trusted Professionals</h3>
                                <p class="text-gray-600">Our trained and certified technicians provide reliable smoke emission testing every time. You can trust that your results are official, accurate, and accepted by authorities, giving you peace of mind.</p>
                </div>
                </div>
                </div>
            </section>
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

        <script>
            function openHelpModal() {
                const modal = document.getElementById('helpModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    console.error('Modal element not found');
                }
            }

            function closeHelpModal() {
                const modal = document.getElementById('helpModal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeHelpModal();
                }
            });
        </script>
    </body>
</html>

