<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\Facades\Image;
use DataTables;
use Illuminate\Support\Facades\Storage;
use PDF;

class Reports extends Controller
{
    public function index()
    {
        return view('report');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:32000',
            'deskripsi' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, jpg, atau gif.',
        ]);

        // Menyimpan foto yang diunggah
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();

            // Membuat instance Intervention Image
            $image = Image::make($photo);

            // Resize atau crop gambar sesuai kebutuhan
            $image->fit(800, 800); // Ubah ukuran sesuai preferensi

            $compressedImagePath = tempnam(sys_get_temp_dir(), 'compressed_');
            $image->save($compressedImagePath, 95);

            $photo = new \Illuminate\Http\UploadedFile(
                $compressedImagePath,
                $photo->getClientOriginalName(),
                $photo->getClientMimeType(),
                $photo->getError(),
                true // 
            );

            // Store the compressed photo
            $photo->storeAs('public/photos', $photoName);
        }
        // Mengambil informasi tahun, bulan, dan tanggal
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        // Menghitung nomor urut (dapat disesuaikan dengan metode Anda)
        $lastNumber = Report::where('no_laporan', 'like', "LAP-$year$month$day-%")->orderByDesc('id')->first(); // Ambil nomor urut terakhir dari database atau file konfigurasi
        if (empty($lastNumber)) {
            $nextNumber = 1;
        } else {
            $temp = explode("-", $lastNumber->no_laporan);
            $nextNumber = intval($temp[2]) + 1;
        }

        // Membuat nomor laporan dengan format yang diinginkan
        $nomorLaporan = "LAP-$year$month$day-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        Report::create([
            'user_id' => Auth::user()->id,
            'tanggal' => now(),
            'no_laporan' => $nomorLaporan,
            'judul' => $request->judul,
            'lokasi' => $request->lokasi,
            'phone' => $request->phone,
            'photo' => $photoName, // Menyimpan nama foto
            'deskripsi' => $request->deskripsi,
        ]);
        toast('Laporan berhasil di buat', 'success');
        return redirect()->route('home');
    }

    public function  user()
    {
        $data = [
            'users' => User::select('name', 'id')->get(),
        ];

        return view('userreport.index', $data);
    }

    public function getData(Request $request)
    {

        $query = Report::query();
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('tanggal', [$start, $end]);
        }
        $query->leftJoin('users as user_creator', 'reports.user_id', '=', 'user_creator.id');
        $query->leftJoin('users as user_updater', 'reports.user_update', '=', 'user_updater.id');
        if (!empty($request->user_id)) {
            $userIds = [];
            foreach ($request->user_id as $val) {
                array_push($userIds, decrypt($val));
            }
            $query->whereIn('reports.user_id', $userIds);
        }
        $query->select('reports.*', 'user_creator.name as creator_name', 'user_updater.name as updater_name');
        return DataTables::of($query)
            ->addColumn('link', function ($report) {
                return route('home', ['no_laporan' => $report->no_laporan]);
            })
            ->toJson();
    }

    public function getDataProfile(Request $request)
    {

        $query = Report::query();
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('tanggal', [$start, $end]);
        }
        $query->leftJoin('users as user_creator', 'reports.user_id', '=', 'user_creator.id');
        $query->leftJoin('users as user_updater', 'reports.user_update', '=', 'user_updater.id');
        $query->where('reports.user_id', '=', dataUser()->id);
        $query->select('reports.*', 'user_creator.name as creator_name', 'user_updater.name as updater_name');
        return DataTables::of($query)
            ->addColumn('link', function ($report) {
                return route('home', ['no_laporan' => $report->no_laporan]);
            })
            ->toJson();
    }

    public function getDataUser(Request $request)
    {
        $query = Report::query();
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('tanggal', [$start, $end]);
        }
        $query->leftJoin('users as user_creator', 'reports.user_id', '=', 'user_creator.id');
        $query->leftJoin('users as user_updater', 'reports.user_update', '=', 'user_updater.id');
        $query->where('reports.user_id', '=', decrypt($request->input('user_id')));
        $query->select('reports.*', 'user_creator.name as creator_name', 'user_updater.name as updater_name');
        return DataTables::of($query)
            ->addColumn('link', function ($report) {
                return route('home', ['no_laporan' => $report->no_laporan]);
            })
            ->toJson();
    }

    public function detail(Request $request)
    {
        return response()->json(Report::where('id', $request->id)->first());
    }

    public function delete(Request $request)
    {
        Report::where('id', $request->id)->delete();
        Alert::success('Success', 'data berhasil di hapus');
        return back();
    }
    public function edit(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:32000',
            'deskripsi' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, jpg, atau gif.',
        ]);

        $dataUpdate = [
            'judul' => $request->judul,
            'phone' => $request->phone,
            'deskripsi' => $request->deskripsi,
        ];

        // Menyimpan foto yang diunggah
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();

            // Membuat instance Intervention Image
            $image = Image::make($photo);

            // Resize atau crop gambar sesuai kebutuhan
            $image->fit(800, 800); // Ubah ukuran sesuai preferensi

            $compressedImagePath = tempnam(sys_get_temp_dir(), 'compressed_');
            $image->save($compressedImagePath, 95);

            // Replace the original photo with the compressed one
            $photo = new \Illuminate\Http\UploadedFile(
                $compressedImagePath,
                $photo->getClientOriginalName(),
                $photo->getClientMimeType(),
                $photo->getError(),
                true // 
            );

            // Store the compressed photo
            $photo->storeAs('public/photos', $photoName);
            $dataUpdate['photo'] = $photoName;

            //hapus photo sebelumnya
            $oldData = Report::where('id', $request->id)->first();
            Storage::delete('public/photos/' . $oldData->photo);
        }

        Report::where('id', $request->id)->update($dataUpdate);
        Alert::success('Success', 'data berhasil di Perbarui');
        return back();
    }
    public function export(Request $request)
    {

        $query = Report::query();
        if ($request->has('start_date') && $request->has('end_date')) {
            if ($request->start_date != null && $request->end_date != null) {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $end = Carbon::parse($request->input('end_date'))->endOfDay();
                $query->whereBetween('tanggal', [$start, $end]);
            }
        }
        $query->where('reports.user_id', $request->user_id);
        $query->leftJoin('users as user_creator', 'reports.user_id', '=', 'user_creator.id');
        $query->leftJoin('users as user_updater', 'reports.user_update', '=', 'user_updater.id');
        $query->select('reports.*', 'user_creator.name as creator_name', 'user_updater.name as updater_name');
        $data = $query->get();
        $pdf = PDF::loadView('report.export', ['data' => $data]);
        return $pdf->download('Laporan-kerja.pdf');
    }

    public function comment($id)
    {
        $id = decrypt($id);
        $comments = ReportComment::where('laporan_id', $id)
            ->leftJoin('users', 'report_comments.user_id', '=', 'users.id')
            ->select(
                'report_comments.*',
                'users.name as name',
                'users.photo as photo',
            )
            ->where('report_comments.reff', null)
            ->get();
        $report = Report::where('reports.id', $id)
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select('reports.*', 'users.name as user_name')
            ->first();
        $data = [
            'comments' => $comments,
            'id' => $id,
            'report' => $report,
        ];

        return view('comment', $data);
    }
    public function commentSave(Request $request)
    {
        $data = [
            'laporan_id' => $request->report_id,
            'user_id' => Auth::user()->id,
            'tanggal' => now(),
            'deskripsi' => $request->deskripsi,
        ];
        if (!empty($request->reff)) {
            $data['reff'] = $request->reff;
        }
        ReportComment::create($data);
        return back();
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);

        Report::where('id', $id)->update([
            'user_update' => Auth::user()->id,
            'status_laporan' => $request->status,
        ]);
        return response()->json(['status' => true]);
    }
}
