<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return view('users.index', ['users' => User::all()]);
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'role' => 'required']);

        // Logic Simple: 4 huruf email + ID calon user
        $pass = substr($request->email, 0, 4) . (User::max('id') + 1);

        User::create($request->all() + ['password' => Hash::make($pass)]);

        return redirect()->route('users.index')->with('success', "Akun dibuat! Password: $pass");
    }

    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users,email,'.$user->id]);

        // Update data dasar
        $user->fill($request->only('name', 'email'))->save();

        // Jika password diisi, baru diupdate
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'Akun diperbarui!');
    }

    public function resetPassword($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Logic simple: 4 huruf email + ID user
        $pass = substr($user->email, 0, 4) . $user->id;

        $user->update([
            'password' => \Hash::make($pass)
        ]);

        return back()->with('success', "Password berhasil direset ke: $pass");
    }

    public function destroy(User $user) {
        $user->delete();
        return back()->with('success', 'User dihapus!');
    }

    public function staffEdit()
    {
        $user = auth()->user(); // Ambil user yang lagi login
        return view('users.staff_edit', compact('user'));
    }

    public function staffUpdate(Request $request)
    {
        $user = auth()->user();
        
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        return back()->with('success', 'Profile updated successfully!');
    }

    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'data-users.xlsx');
    }
}