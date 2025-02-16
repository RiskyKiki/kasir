<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,petugas',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        User::create([
            'username'   => $request->username,
            'email'      => $request->email,
            'role'       => $request->role,
            'password'   => Hash::make($request->password),
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'User berhasil ditambahkan!'
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'id'         => $user->id,
            'username'   => $user->username ?? '-',
            'email'      => $user->email ?? '-',
            'role'       => $user->role ?? '-',
            'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-',
            'creator'    => $user->creator ? $user->creator->username : '-',
            'updated_at' => $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '-',
            'updater'    =>  $user->updater ? $user->updater->username : '-',
        ]);
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }


    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,petugas',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        $user->update([
            'username'   => $request->username,
            'email'      => $request->email,
            'role'       => $request->role,
            'password'   => $request->password ? Hash::make($request->password) : $user->password,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'User berhasil diubah!'
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();
        return response()->json([
            'success' => 'User berhasil dihapus!'
        ]);    }
}