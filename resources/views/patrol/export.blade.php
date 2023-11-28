<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Jadwal Patroli</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
@php
    use Carbon\Carbon;
@endphp

<style>
    table,
    th,
    td {
        border: 1px solid;
    }

    th {
        text-align: center;
    }

    td {
        text-align: center;
    }
</style>



<body>
    <h1 style="text-align:center; margin-bottom: 20px;">Jadwal Patroli PK4L FMIPA</h1>
    <p>Laporan oleh     : {{ Auth::user()->name }}</p>
    <p>Tanggal Laporan  : {{ Carbon::now()->format('d M Y H:i:s') }}</p>
    <table class="table">
        <thead>
            <tr>
                <td>Nama</td>
                <td>Tanggal</td>
                <td>Jam Mulai</td>
                <td>Jam Selesai</td>
                <td>Penugasan</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td>{{ $item->jam_awal }}</td>
                    <td>{{ $item->jam_akhir }}</td>
                    <td>{{ $item->penugasan }}</td>

                </tr>

            @endforeach

        </tbody>

    </table>


</body>
</html>
