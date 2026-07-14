<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // <-- Tambahan untuk menghilangkan error Intelephense

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['administrator', 'admin'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'User baru berhasil ditambahkan!');
    }

    public function destroy(User $user)
    {
        // Perbaikan: Menggunakan Auth::id() standar
        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri!']);
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}