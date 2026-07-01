<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class GateScannerController extends Controller
{
    /**
     * Display a listing of the gate scanners for the organizer.
     */
    public function index()
    {
        $scanners = User::role('Gate Scanner')
            ->where('organizer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('organizer.scanners.index', compact('scanners'));
    }

    /**
     * Show the form for creating a new gate scanner.
     */
    public function create()
    {
        return view('organizer.scanners.create');
    }

    /**
     * Store a newly created gate scanner in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organizer_id' => Auth::id(),
        ]);

        $user->assignRole('Gate Scanner');

        return redirect()->route('organizer.scanners.index')->with('success', 'Akun Gate Scanner berhasil dibuat.');
    }

    /**
     * Remove the specified gate scanner from storage.
     */
    public function destroy(User $scanner)
    {
        // Ensure the scanner belongs to this organizer
        if ($scanner->organizer_id !== Auth::id()) {
            abort(403);
        }

        $scanner->delete();

        return redirect()->route('organizer.scanners.index')->with('success', 'Akun Gate Scanner berhasil dihapus.');
    }
}
