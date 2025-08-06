<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::with('role')->get(); // from laravel,
        return Inertia::render('profile-management', [
            // 'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Prevent a logged-in user from changing their own role if they are the system-admin (id 1)
        if (Auth::user()->id === $user->id && $user->role_id === 1) {
            return back()->with('error', 'You cannot change the role of the system administrator.');
        }

        $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        // Update the user's role_id directly
        $user->update([
            'role_id' => $request->role_id,
        ]);

        // Return a redirect or a response
        return back()->with('success', 'User role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // 2. Perform the deletion.
        $user->delete();

        // 3. Redirect back to the previous page with a success message.
        return redirect()->back()
            ->with('success', 'User deleted successfully.');
    }
}
