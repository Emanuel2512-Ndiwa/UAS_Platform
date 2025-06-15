<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function list_users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function delete_user($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus.');
    }
}