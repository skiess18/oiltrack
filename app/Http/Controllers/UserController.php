<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|min:6|confirmed',

            'role' => 'required|in:admin,dispatcher,driver',

        ]);

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make($request->password),

            'role' => $request->role,

        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Потребителят беше създаден успешно.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([

            'name' => 'required|max:255',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'role' => 'required|in:admin,dispatcher,driver',

        ]);

        $user->update([

            'name' => $request->name,

            'email' => $request->email,

            'role' => $request->role,

        ]);

        if ($request->filled('password')) {

            $user->update([

                'password' => Hash::make($request->password),

            ]);

        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Потребителят беше обновен.');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {

            return back()->with(
                'error',
                'Не можете да изтриете собствения си профил.'
            );

        }

        $user->delete();

        return back()->with(
            'success',
            'Потребителят беше изтрит.'
        );
    }
}
