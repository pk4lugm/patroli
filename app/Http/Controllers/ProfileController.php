<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{
    public function index()
    {
        $data = [
            'user' => Auth::user(),
        ];
        return view('profile.index', $data);
    }
    public function edit()
    {
        $data = [
            'user' => Auth::user(),
        ];

        return view('profile.edit', $data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:32000',
            'password' => 'nullable|string|max:255',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, jpg, atau gif.',
        ]);

        $data = [
            'name' => $request->name,
        ];
        if (!empty($request->password)) {
            $data['password'] = bcrypt(trim($request->password));
        }
        if (!empty($request->phone)) {
            $data['phone'] = trim($request->phone);
        }
        if (!empty($request->address)) {
            $data['address'] = trim($request->address);
        }

        // Menyimpan foto yang diunggah
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();

            // Membuat instance Intervention Image
            $image = Image::make($photo);

            // Resize atau crop gambar sesuai kebutuhan
            $image->fit(800, 800); // Ubah ukuran sesuai preferensi

            // Compress image to 60% quality
            $compressedImagePath = tempnam(sys_get_temp_dir(), 'compressed_');
            $image->save($compressedImagePath, 95);

            // Replace the original photo with the compressed one
            $photo = new \Illuminate\Http\UploadedFile(
                $compressedImagePath,
                $photo->getClientOriginalName(),
                $photo->getClientMimeType(),
                $photo->getError(),
                true // Indicates whether the file should be moved or not. It's true because we generated a temp file.
            );

            // Store the compressed photo
            $photo->storeAs('public/profiles', $photoName);
            $data['photo'] = $photoName;
        }
        User::where('id', Auth::user()->id)->update($data);
        Alert::toast('Profile berhasil di rubah', 'success');
        return back();
    }

    public function detail($id)
    {
        $id = decrypt($id);
        $user = User::where('id', $id)->first();
        if (empty($user)) {
            return back();
        }
        $data = [
            'user' => $user,

        ];
        return view('profiledetail', $data);
    }
}
