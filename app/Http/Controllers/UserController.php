<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\Facades\Image;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Users',
        ];
        return view('users.index', $data);
    }

    public function getData()
    {
        return DataTables::eloquent(
            User::query()
        )
            ->toJson();
    }
    public function detail(Request $request)
    {
        return response()->json(User::where('id', $request->id)->first());
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
    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'level_user' => 'required',
            'is_active' => 'required',
            'phone' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:32000',
            'address' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, jpg, atau gif.',
        ]);

        $dataUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'level_user' => $request->level_user,
            'is_active' => $request->is_active,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Menyimpan foto yang diunggah
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();

            // Membuat instance Intervention Image
            $image = Image::make($photo);

            // Resize atau crop gambar sesuai kebutuhan
            $image->fit(800, 800); // Ubah ukuran sesuai preferensi
            $photo->storeAs('public/profiles', $photoName);
            $dataUpdate['photo'] = $photoName;

            //hapus poyho sebelumnya
            $oldData = User::where('id', $request->id)->first();
            Storage::delete('public/profiles/' . $oldData->photo);
        }

        User::where('id', $request->id)->update($dataUpdate);
        Alert::success('Success', 'data berhasil di Perbarui');
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        User::where('id', $request->id)->delete();
        Alert::success('Success', 'data berhasil dihapus');
        return back();
    }
}
