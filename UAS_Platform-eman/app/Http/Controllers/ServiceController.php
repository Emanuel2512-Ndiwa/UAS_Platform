<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // Menampilkan halaman utama (home)
    public function indexHome()
    {
        $services = Service::all();
        return view('pages.home', compact('services'));
    }

    // Menampilkan daftar semua layanan
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    // Menampilkan detail layanan berdasarkan ID
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Untuk admin: menyimpan layanan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|string'
        ]);

        Service::create($request->all());
        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan');
    }

    // Untuk admin: update layanan
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($request->all());
        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui');
    }

    // Untuk admin: hapus layanan
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus');
    }
}
