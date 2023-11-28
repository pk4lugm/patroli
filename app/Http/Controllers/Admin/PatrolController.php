<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatrolSchedule;
use App\Models\User;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class PatrolController extends Controller
{
    public function index()
    {

        $data = [
            'users' => User::where('level_user', 2)->select('name', 'id')->get(),
        ];

        return view('patrol.index', $data);
    }

    public function getData(Request $request)
    {
        $query = PatrolSchedule::query();
        // Filter rentang tanggal
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('tanggal', [$start, $end]);
        }
        $query->leftJoin('users', 'patrol_schedules.user_id', '=', 'users.id');
        $query->select('patrol_schedules.*', 'users.name as name');
        $query->orderByDesc('tanggal');

        return DataTables::of($query)
            ->editColumn('tanggal', function ($data) {
                return Carbon::parse($data->tanggal)->format('d M Y'); // Format sesuai keinginan Anda
            })
            ->toJson();
    }

    public function add(Request $request)
    {
        $request->validate([
            'userid' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_awal' => 'required|string|max:5|regex:/^[0-9]{2}:[0-9]{2}$/',
            'jam_akhir' => 'nullable|string|max:5|regex:/^[0-9]{2}:[0-9]{2}$/',
            'penugasan' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'regex' => ':attribute harus berformat HH:MM.',
            'exists' => 'data Staff tidak di temukan.',
        ]);

        PatrolSchedule::create([
            'user_id' => $request->userid,
            'tanggal' => $request->tanggal,
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'penugasan' => $request->penugasan,
        ]);



        Alert::success('Success', 'data berhasil di Tambah');
        return back();
    }

    public function detail(Request $request)
    {
        return response()->json(PatrolSchedule::where('id', $request->id)->first());
    }
    public function edit(Request $request)
    {
        $request->validate([
            'userid' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_awal' => 'required|string',
            'jam_akhir' => 'nullable|string',
            'penugasan' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal.',
            'regex' => ':attribute harus berformat HH:MM.',
            'exists' => 'data Staff tidak di temukan.',
        ]);

        PatrolSchedule::where('id', $request->id)->update([
            'user_id' => $request->userid,
            'tanggal' => $request->tanggal,
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'penugasan' => $request->penugasan,
        ]);

        Alert::success('Success', 'data berhasil di Perbarui');
        return back();
    }
    public function export(Request $request)
    {
        $start = Carbon::parse($request->input('start_date'))->startOfDay();
        $end = Carbon::parse($request->input('end_date'))->endOfDay();

        // DB::table('resport')->select();

        $data = PatrolSchedule::whereBetween('tanggal', [$start, $end])
            ->leftJoin('users', 'patrol_schedules.user_id', '=', 'users.id')
            ->select(
                'patrol_schedules.*',
                'users.name as name',
            )
            ->orderByDesc('tanggal')
            ->get();

        $pdf = PDF::loadView('patrol.export', ['data' => $data]);

        return $pdf->stream($start . '-' . $end . '.pdf');
    }
    public function delete(Request $request)
    {
        PatrolSchedule::where('id', $request->id)->delete();
        Alert::success('Success', 'data berhasil di hapus');
        return back();
    }
}
