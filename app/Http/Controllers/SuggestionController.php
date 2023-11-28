<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SuggestionController extends Controller
{
    public function index()
    {
        return view('suggestion');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, jpg, atau gif.',
        ]);

        Suggestion::create([
            'user_id' => Auth::user()->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi
        ]);

        Alert::toast('Terimaksih saran anda telah diterima','success');
        return redirect()->route('home');
    }
}
