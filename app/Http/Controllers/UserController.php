<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show a list of users
    public function index()
    {
        $users = User::all();
        return view('admin.showusermanagement', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        return view('users.create');
    }

    // Store the new user in the database
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'barangay' => 'nullable|string|max:255',
            'usertype' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'barangay' => $request->barangay,
            'usertype' => $request->usertype,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    // Show the form to edit the user's details
    public function edit(User $user)
    {
        return view('admin.editusers', compact('user'));
    }

    // Update the user's details
    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'barangay' => 'required|string|max:255',
            'usertype' => 'required|string',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user details
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->barangay = $request->barangay;
        $user->usertype = $request->usertype;
        $user->save();

        // Redirect to admin home page after saving
        return back()->with('success', 'User updated successfully!');    }


    // Delete the user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
