<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Smoke Emission Test - Super Carry Emission Testing Co</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    
    <style>
        .back-button-with-text {
            display: inline-flex;
            align-items: center;
            background: white;
            color: #4B5563;
            padding: 8px 16px 8px 8px;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            text-decoration: none;
            font-weight: 500;
            margin-right: 16px;
        }
        
        .back-button-with-text:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }
        
        .back-button-with-text:active {
            transform: translateY(0);
        }
        
        .back-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f3f4f6;
            margin-right: 8px;
        }
        
        .back-text {
            color: #4B5563;
            font-size: 0.95rem;
        }
    </style>
    
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
            <div class="flex items-center">
                <a href="{{ url()->previous() }}" class="back-button-with-text flex items-center">
                    <span class="back-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="back-text ml-2">Back</span>
                </a>
                <div class="app-logo">
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
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Schedule Smoke Emission Test</h1>
                <p class="text-gray-600 mt-1">Book your vehicle for emission testing</p>
            </div>

            <!-- Schedule Form -->
            <div class="max-w-4xl mx-auto">
                <form method="POST" action="{{ route('schedules.store') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Mobile Number -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Contact Information</h3>
                            <p class="card-subtitle">We need your phone number to notify you when it is almost your turn</p>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="mobile_number" class="form-label">Mobile Number</label>
                                <input type="tel" name="mobile_number" id="mobile_number" required pattern="09[0-9]{9}" maxlength="11" placeholder="09XXXXXXXXX" value="{{ old('mobile_number') }}" class="form-input">
                                <p class="form-help">Our worker will contact you whenever your time is coming up or it’s almost your time.</p>
                                @error('mobile_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Vehicle Information</h3>
                            <p class="card-subtitle">Select the vehicle you want to test</p>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="vehicle_id" class="form-label">Select Vehicle</label>
                                <select name="vehicle_id" id="vehicle_id" required class="form-select">
                            <option value="">Choose your vehicle</option>
                                                         @foreach($vehicles as $vehicle)
                                 <option value="{{ $vehicle->id }}" data-vehicle-type="{{ $vehicle->vehicle_type }}">
                                     {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->plate_number }} ({{ strtoupper($vehicle->vehicle_type) }})
                                 </option>
                             @endforeach
                        </select>
                        @error('vehicle_id')
                                    <p class="form-error">{{ $message }}</p>
                        @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Selection -->
                    <div class="card interactive">
                        <div class="card-header">
                            <h3 class="card-title gradient-text">Schedule Details</h3>
                            <p class="card-subtitle">Choose your preferred date and time slot</p>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="schedule_slot" class="form-label">Available Time Slots</label>
                                <select name="schedule_slot" id="schedule_slot" required class="form-select">
                                    <option value="">Select a date and time</option>
                                    @php
                                        $startDate = now()->addDay();
                                        $endDate = now()->addDays(30);
                                        $timeSlots = [
                                            '08:00' => '8:00 AM - 9:00 AM',
                                            '10:00' => '10:00 AM - 11:00 AM',
                                            '13:00' => '1:00 PM - 2:00 PM',
                                            '15:00' => '3:00 PM - 4:00 PM'
                                        ];
                                    @endphp
                                    
                                    @for($date = $startDate; $date->lte($endDate); $date->addDay())
                                        @if(!$date->isWeekend())
                                            <optgroup label="{{ $date->format('l, F j, Y') }}">
                                                @foreach($timeSlots as $time => $label)
                                                    @php
                                                        $slotDateTime = $date->copy()->setTimeFromTimeString($time);
                                                        $slotKey = $date->format('Y-m-d') . ' ' . $time . ':00';
                                                        $isBooked = false;
                                                        
                                                        // Check if this slot is booked
                                                        foreach ($bookedTimeSlots as $bookedSlot) {
                                                            $bookedDate = \Carbon\Carbon::parse($bookedSlot['test_date'])->format('Y-m-d');
                                                            $bookedTime = \Carbon\Carbon::parse($bookedSlot['test_time'])->format('H:i');
                                                            
                                                            if ($bookedDate === $date->format('Y-m-d') && $bookedTime === $time) {
                                                                $isBooked = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if($isBooked)
                                                        <option value="" disabled>Time Not Available ({{ $label }})</option>
                                                    @else
                                                        <option value="{{ $slotKey }}">{{ $label }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endfor
                                </select>
                                <input type="hidden" name="test_date" id="test_date">
                                <input type="hidden" name="test_time" id="test_time">
                                <p class="form-help">Select your preferred time slot. Each test session is 1 hour long.</p>
                                @error('schedule_slot')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Test Type and Pricing -->
                    <!-- Test Type & Pricing -->
                    <div class="card interactive">
                        <div class="card-header">
                            <h3 class="card-title gradient-text">Test Type & Pricing</h3>
                            <p class="card-subtitle">Select your test type and view pricing details</p>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="test_type" class="form-label">Test Type</label>
                                <select name="test_type" id="test_type" required class="form-select">
                            <option value="">Choose test type</option>
                            <option value="initial">Initial Test</option>
                            <option value="renewal">Renewal Test</option>
                            <option value="retest">Retest</option>
                        </select>
                        @error('test_type')
                                    <p class="form-error">{{ $message }}</p>
                        @enderror
                            </div>
                        
                        <!-- Pricing Information -->
                            <div id="pricing-info" class="mt-6 p-6 bg-gray-50 rounded-lg">
                                <h4 class="font-semibold text-gray-900 mb-4">Pricing Breakdown</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Total Amount:</span>
                                        <div class="font-semibold text-gray-900" id="total-amount">₱0.00</div>
                                </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Downpayment (50%):</span>
                                    <div class="font-semibold text-blue-600" id="downpayment-amount">₱0.00</div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Remaining Balance:</span>
                                        <div class="font-semibold text-gray-900" id="remaining-balance">₱0.00</div>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-blue-800 text-sm">💡 Pay the downpayment to secure your booking. The remaining balance is due on the test date.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="card interactive">
                        <div class="card-header">
                            <h3 class="card-title gradient-text">Additional Information</h3>
                            <p class="card-subtitle">Any special requirements or notes</p>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" placeholder="Any special requirements or notes..." class="form-textarea"></textarea>
                        @error('notes')
                                    <p class="form-error">{{ $message }}</p>
                        @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-center pt-6">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Create Schedule with Payment Proof
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="card mt-8">
                <div class="card-header">
                    <h3 class="card-title">What to Expect</h3>
                    <p class="card-subtitle">Important information for your test day</p>
                </div>
                <div class="card-content">
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Please arrive 15 minutes before your scheduled time</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Bring your vehicle registration and insurance documents</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Ensure your vehicle is in good working condition</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Test typically takes 30-45 minutes to complete</span>
                        </li>
                </ul>
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


    <script>
        // Booked time slots from the server
        const bookedTimeSlots = @json($bookedTimeSlots);
        
        // Handle schedule slot selection
        document.addEventListener('DOMContentLoaded', function() {
            const scheduleSlot = document.getElementById('schedule_slot');
            const form = document.querySelector('form');
            const submitButton = form.querySelector('button[type="submit"]');
            
            // Function to handle slot selection
            function handleSlotSelection() {
                const selectedOption = scheduleSlot.options[scheduleSlot.selectedIndex];
                const dateInput = document.getElementById('test_date');
                const timeInput = document.getElementById('test_time');
                
                if (selectedOption && selectedOption.value) {
                    // Format: 'Y-m-d H:i:s'
                    const [date, time] = selectedOption.value.split(' ');
                    dateInput.value = date;
                    timeInput.value = time;
                    
                    // Enable the form submission
                    submitButton.disabled = false;
                    console.log('Selected slot:', { date, time });
                } else {
                    dateInput.value = '';
                    timeInput.value = '';
                    submitButton.disabled = true;
                }
            }
            
            // Handle form submission
            form.addEventListener('submit', function(e) {
                const dateInput = document.getElementById('test_date');
                const timeInput = document.getElementById('test_time');
                
                if (!dateInput.value || !timeInput.value) {
                    e.preventDefault();
                    alert('Please select a valid time slot');
                    return false;
                }
                
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            });
            
            // Initialize
            scheduleSlot.addEventListener('change', handleSlotSelection);
            submitButton.disabled = true;
            
            // If there's a validation error, try to restore the selected value
            @if($errors->any())
                const selectedValue = '{{ old('schedule_slot') }}';
                if (selectedValue) {
                    scheduleSlot.value = selectedValue;
                    handleSlotSelection();
                }
            @endif
        });
        
        // Time slot availability checking
        function checkTimeSlotAvailability() {
            const selectedDate = document.getElementById('test_date').value;
            const selectedTime = document.getElementById('test_time').value;
            const availabilityDiv = document.getElementById('time-slot-availability');
            const availabilityStatus = document.getElementById('availability-status');
            const availabilityMessage = document.getElementById('availability-message');
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (selectedDate && selectedTime) {
                // Check if the selected time slot conflicts with existing schedules
                const isConflict = checkForTimeConflict(selectedDate, selectedTime);
                
                if (isConflict) {
                    // Time slot is not available
                    availabilityDiv.classList.remove('schedule-availability-check');
                    availabilityStatus.textContent = '❌ Not Available';
                    availabilityStatus.className = 'schedule-availability-status schedule-availability-status-unavailable';
                    availabilityMessage.textContent = 'This time slot conflicts with an existing schedule. Tests take 2 hours, so please choose a different time.';
                    availabilityMessage.className = 'schedule-availability-message schedule-availability-message-unavailable';
                    
                    // Disable the submit button
                    submitButton.disabled = true;
                    submitButton.classList.add('btn-disabled');
                } else {
                    // Time slot is available
                    availabilityDiv.classList.remove('schedule-availability-check');
                    availabilityStatus.textContent = '✅ Available';
                    availabilityStatus.className = 'schedule-availability-status schedule-availability-status-available';
                    availabilityMessage.textContent = 'This time slot is available for scheduling.';
                    availabilityMessage.className = 'schedule-availability-message schedule-availability-message-available';
                    
                    // Enable the submit button
                    submitButton.disabled = false;
                    submitButton.classList.remove('btn-disabled');
                }
            } else {
                availabilityDiv.classList.add('schedule-availability-check');
                submitButton.disabled = false;
                submitButton.classList.remove('btn-disabled');
            }
        }
        
        // Check for time conflicts (1.5-hour test duration)
        function checkForTimeConflict(date, time) {
            if (!bookedTimeSlots[date] || bookedTimeSlots[date].length === 0) {
                return false; // No schedules on this date
            }
            
            const testDurationMinutes = 90; // 1.5 hours in minutes
            const testDurationMs = testDurationMinutes * 60 * 1000;
            
            const requestedTime = new Date(date + 'T' + time);
            const requestedEnd = new Date(requestedTime.getTime() + testDurationMs);
            
            for (const bookedTime of bookedTimeSlots[date]) {
                const bookedStart = new Date(date + 'T' + bookedTime);
                const bookedEnd = new Date(bookedStart.getTime() + testDurationMs);
                
                // Convert to timestamps for easier comparison
                const reqStart = requestedTime.getTime();
                const reqEnd = requestedEnd.getTime();
                const bookStart = bookedStart.getTime();
                const bookEnd = bookedEnd.getTime();
                
                // Check for any overlap
                if ((reqStart >= bookStart && reqStart < bookEnd) ||  // Request starts during a booking
                    (reqEnd > bookStart && reqEnd <= bookEnd) ||     // Request ends during a booking
                    (reqStart <= bookStart && reqEnd >= bookEnd)) {   // Request completely contains a booking
                    return true; // Conflict found
                }
            }
            
            return false; // No conflicts
        }
        
        // Update time slot dropdown based on selected date
        function updateTimeSlots() {
            // This function is no longer needed as we're using a combined dropdown
            // Keeping it for backward compatibility
            return;
            
            if (!selectedDate) {
                return;
            }
            
            // Enable all options first
            Array.from(timeSelect.options).forEach(option => {
                if (option.value) { // Skip the default "Select a time" option
                    option.disabled = false;
                }
            });
            
            // Disable booked time slots
            if (bookedTimeSlots[selectedDate]) {
                const bookedTimes = bookedTimeSlots[selectedDate];
                
                Array.from(timeSelect.options).forEach(option => {
                    if (option.value && bookedTimes.includes(option.value)) {
                        option.disabled = true;
                        option.text = `${option.value} - Not Available`;
                    } else if (option.value) {
                        option.text = option.value; // Reset text if it was previously marked as not available
                    }
                });
            }
            
            // Reset the selected time if it's now disabled
            if (timeSelect.value && timeSelect.options[timeSelect.selectedIndex].disabled) {
                timeSelect.value = '';
                checkTimeSlotAvailability();
            }
        }
        
        // Event listeners
        document.getElementById('test_date').addEventListener('change', function() {
            updateTimeSlots();
            checkTimeSlotAvailability();
        });
        document.getElementById('test_time').addEventListener('change', checkTimeSlotAvailability);
        
        // Initialize time slots when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateTimeSlots();
        });
        
        // Vehicle type pricing
        const vehicleTypePricing = {
            'motorcycle': {
                'initial': 800.00,
                'renewal': 600.00,
                'retest': 400.00
            },
            'tricycle': {
                'initial': 900.00,
                'renewal': 700.00,
                'retest': 500.00
            },
            'auto': {
                'initial': 1200.00,
                'renewal': 900.00,
                'retest': 600.00
            },
            'suv': {
                'initial': 1800.00,
                'renewal': 1400.00,
                'retest': 900.00
            },
            'truck': {
                'initial': 2500.00,
                'renewal': 2000.00,
                'retest': 1200.00
            },
            'bus': {
                'initial': 3500.00,
                'renewal': 2800.00,
                'retest': 1800.00
            }
        };

        // Pricing display
        function updatePricing() {
            const selectedVehicle = document.getElementById('vehicle_id').options[document.getElementById('vehicle_id').selectedIndex];
            const selectedTestType = document.getElementById('test_type').options[document.getElementById('test_type').selectedIndex];
            const pricingInfo = document.getElementById('pricing-info');
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (selectedVehicle.value && selectedTestType.value) {
                const vehicleType = selectedVehicle.dataset.vehicleType;
                const testType = selectedTestType.value;
                const price = vehicleTypePricing[vehicleType][testType];
                const downpayment = price * 0.5;
                const remaining = price - downpayment;
                
                // Show pricing info
                pricingInfo.classList.remove('schedule-pricing-info');
                document.getElementById('total-amount').textContent = '₱' + price.toFixed(2);
                document.getElementById('downpayment-amount').textContent = '₱' + downpayment.toFixed(2);
                document.getElementById('remaining-balance').textContent = '₱' + remaining.toFixed(2);
                
                // Update submit button
                submitButton.textContent = `Create Schedule with Payment Proof (₱${downpayment.toFixed(2)} Downpayment)`;
            } else {
                pricingInfo.classList.add('schedule-pricing-info');
                submitButton.textContent = 'Create Schedule with Payment Proof (₱0.00 Downpayment)';
            }
        }

        // Event listeners for pricing updates
        document.getElementById('vehicle_id').addEventListener('change', updatePricing);
        document.getElementById('test_type').addEventListener('change', updatePricing);
    </script>
</body>
</html>
