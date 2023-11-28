<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'users' => User::select('name', 'id')->get(),
        ];
        return view('report.index', $data);
    }

    public function getData(Request $request)
    {
        $query = Report::query();

        // clear
        if ($request->has('start_date') && $request->has('end_date')) {
            if ($request->start_date != null && $request->end_date != null) {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $end = Carbon::parse($request->input('end_date'))->endOfDay();
                $query->whereBetween('tanggal', [$start, $end]);
            }
        }
        if (!empty($request->user_id)) {
            $userIds = [];
            foreach ($request->user_id as $val) {
                array_push($userIds, decrypt($val));
            }
            $query->whereIn('reports.user_id', $userIds);
        }

        $query->leftJoin('users as user_creator', 'reports.user_id', '=', 'user_creator.id');
        $query->leftJoin('users as user_updater', 'reports.user_update', '=', 'user_updater.id');
        $query->select('reports.*', 'user_creator.name as creator_name', 'user_updater.name as updater_name');
        return DataTables::of($query)
            ->addColumn('link', function ($report) {
                return route('home', ['no_laporan' => $report->no_laporan]);
            })
            ->toJson();
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

        if (!empty(json_decode($request->user_id))) {
            $userIds = [];
            foreach (json_decode($request->user_id) as $val) {
                array_push($userIds, decrypt($val));
            }
            $query->whereIn('reports.user_id', $userIds);
        }

        $query->leftJoin('users as user_creator', 'reports.user_id', '=', 'user_creator.id');
        $query->leftJoin('users as user_updater', 'reports.user_update', '=', 'user_updater.id');
        $query->select('reports.*', 'user_creator.name as creator_name', 'user_updater.name as updater_name');
        $data = $query->get();
        $pdf = PDF::loadView('report.export', ['data' => $data]);

        return $pdf->download('Laporan-kerja.pdf');
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
            'status_laporan' => 'required|string|max:255',
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
            'status_laporan' => $request->status_laporan,
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
            $image->fit(800, 600); // Ubah ukuran sesuai preferensi
            $photo->storeAs('public/photos', $photoName);
            $dataUpdate['photo'] = $photoName;

            //hapus poyho sebelumnya
            $oldData = Report::where('id', $request->id)->first();
            Storage::delete('public/photos/' . $oldData->photo);
        }

        Report::where('id', $request->id)->update($dataUpdate);
        Alert::success('Success', 'data berhasil di Perbarui');
        return back();
    }
}
