<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function vehicles()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles()->latest()->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);
        $user->update($validated);
        return back()->with('status', 'Profile updated');
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'digits:4'],
            'plate_number' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', 'in:motorcycle,tricycle,auto,suv,truck,bus'],
        ]);
        $request->user()->vehicles()->create($validated);
        return redirect()->route('vehicles.index')->with('status', 'Vehicle added successfully!');
    }

    public function destroyVehicle(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('status', 'Vehicle removed successfully!');
    }
}


