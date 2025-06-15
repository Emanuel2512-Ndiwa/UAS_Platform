<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:karyawan']);
    }

    public function index()
    {
        return view('karyawan.dashboard');
    }

    public function tasks()
    {
        return view('karyawan.tasks');
    }
}