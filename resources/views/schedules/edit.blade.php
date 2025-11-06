<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule - Super Carry Emission Testing Co</title>
            @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
</head>
<body class="min-h-screen bg-blue-100" style="background-color:#e0f2fe;">
    <!-- Header -->
    <header class="app-header">
        <div class="app-nav">
            <div class="app-logo">
                <h1 class="schedule-header-title">Super Carry Emission Testing Co</h1>
            </div>
            <div class="app-nav-actions">
                <a href="{{ route('profile.show') }}" class="schedule-user-profile">
                    <div class="schedule-user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="schedule-user-name">{{ auth()->user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="app-logout-btn">Sign out</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="schedule-main">
        <div class="schedule-container-lg">
            <!-- Page Header -->
            <div class="schedule-page-header">
                <div>
                    <h2 class="schedule-page-title">Edit Schedule</h2>
                    <p class="schedule-page-subtitle">Modify your smoke emission test appointment</p>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="schedule-form-container">
                <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="schedule-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Vehicle Selection -->
                    <div class="schedule-form-group">
                        <label for="vehicle_id" class="schedule-form-label">Select Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id" required class="schedule-form-select">
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ $schedule->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="schedule-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Test Date and Time -->
                    <div class="schedule-form-grid">
                        <div class="schedule-form-group">
                            <label for="test_date" class="schedule-form-label">Test Date</label>
                            <input type="date" name="test_date" id="test_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ $schedule->test_date->format('Y-m-d') }}" class="schedule-form-input">
                            <p class="schedule-form-help">Select a date for your emission test</p>
                            @error('test_date')
                                <p class="schedule-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="schedule-form-group">
                            <label for="test_time" class="schedule-form-label">Test Time</label>
                            <input type="time" name="test_time" id="test_time" required value="{{ \Carbon\Carbon::parse($schedule->test_time)->format('H:i') }}" class="schedule-form-input">
                            <p class="schedule-form-help">Select a time (test takes 2 hours)</p>
                            @error('test_time')
                                <p class="schedule-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Time Slot Availability Check -->
                    <div id="time-slot-availability" class="schedule-availability-check">
                        <div class="schedule-availability-content">
                            <div class="schedule-availability-header">
                                <span class="schedule-availability-label">Time Slot Availability:</span>
                                <span id="availability-status" class="schedule-availability-status"></span>
                            </div>
                            <p id="availability-message" class="schedule-availability-message"></p>
                        </div>
                    </div>

                    <!-- Test Type -->
                    <div class="schedule-form-group">
                        <label for="test_type" class="schedule-form-label">Test Type</label>
                        <select name="test_type" id="test_type" required class="schedule-form-select">
                            <option value="initial" {{ $schedule->test_type === 'initial' ? 'selected' : '' }}>Initial Test</option>
                            <option value="renewal" {{ $schedule->test_type === 'renewal' ? 'selected' : '' }}>Renewal Test</option>
                            <option value="retest" {{ $schedule->test_type === 'retest' ? 'selected' : '' }}>Retest</option>
                        </select>
                        @error('test_type')
                            <p class="schedule-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="schedule-form-group">
                        <label for="notes" class="schedule-form-label">Additional Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Any special requirements or notes..." class="schedule-form-textarea">{{ old('notes', $schedule->notes) }}</textarea>
                        @error('notes')
                            <p class="schedule-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="schedule-form-actions-between">
                        <a href="{{ route('schedules.show', $schedule) }}" class="schedule-form-back">
                            ← Back to Schedule
                        </a>
                        <div class="schedule-details-action-group">
                            <a href="{{ route('schedules.show', $schedule) }}" class="schedule-form-cancel">
                                Cancel
                            </a>
                            <button type="submit" class="schedule-form-submit-sm">
                                Update Schedule
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Current Schedule Info -->
            <div class="schedule-info-section">
                <h3 class="schedule-info-title">Current Schedule Information</h3>
                <div class="schedule-pricing-grid">
                    <div class="schedule-pricing-item">
                        <span class="schedule-pricing-label">Vehicle:</span>
                        <div class="schedule-pricing-value">{{ $schedule->vehicle->year }} {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }}</div>
                    </div>
                    <div class="schedule-pricing-item">
                        <span class="schedule-pricing-label">Date:</span>
                        <div class="schedule-pricing-value">{{ $schedule->test_date->format('M d, Y') }}</div>
                    </div>
                    <div class="schedule-pricing-item">
                        <span class="schedule-pricing-label">Time:</span>
                        <div class="schedule-pricing-value">{{ \Carbon\Carbon::parse($schedule->test_time)->format('g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="app-footer-content">
            <p>&copy; 2025 Super Carry Emission Testing Co. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Booked time slots from the server (excluding current schedule)
        const bookedTimeSlots = @json($bookedTimeSlots);
        
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
        
        // Check for time conflicts (2-hour test duration)
        function checkForTimeConflict(date, time) {
            if (!bookedTimeSlots[date]) {
                return false; // No schedules on this date
            }
            
            const requestedTime = new Date(date + 'T' + time);
            const requestedEnd = new Date(requestedTime.getTime() + (2 * 60 * 60 * 1000)); // +2 hours
            
            for (const bookedTime of bookedTimeSlots[date]) {
                const bookedStart = new Date(date + 'T' + bookedTime);
                const bookedEnd = new Date(bookedStart.getTime() + (2 * 60 * 60 * 1000)); // +2 hours
                
                // Check for overlap
                if (requestedTime < bookedEnd && requestedEnd > bookedStart) {
                    return true; // Conflict found
                }
            
            return false; // No conflicts
        }
        
        // Event listeners
        document.getElementById('test_date').addEventListener('change', checkTimeSlotAvailability);
        document.getElementById('test_time').addEventListener('change', checkTimeSlotAvailability);
        
        // Check availability on page load
        window.addEventListener('load', checkTimeSlotAvailability);
    </script>
</body>
</html>
