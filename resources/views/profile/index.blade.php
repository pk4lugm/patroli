@extends('layouts.app')

@section('content')
<style>
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #aaa;
        border-radius: 3px;
        padding: 5px;
        background-color: transparent;
        color: inherit;
        margin-left: 3px;
        width: 150px;
        height: 30px;
        margin-bottom: 5px;
    }
</style>

    <div class="container profile-area">
        <div class="profile">
            <div class="main-profile">
                <div class="left-content">
                    <span>{{ $user->email }}</span>
                    <h5 class="mt-1">{{ $user->name }}</h5>
                    <h6 class="text-primary font-w400">{{ $user->phone }}</h6>
                </div>
                <div class="right-content">
                    <div class="upload-box">
                        @if (empty($user->photo))
                            <img src="{{ asset('template') }}/assets/images/stories/small/pic4.jpg" alt="{{ $user->name }}">
                        @else
                            <img src="{{ asset('storage/profiles/' . $user->photo) }}" alt="{{ $user->name }}">
                        @endif
                        <a href="{{ route('profile.edit') }}" class="upload-btn"><i class="fa-solid fa-pencil"></i></a>
                    </div>
                </div>
            </div>
            <div class="info">
                <h6>Alamat</h6>
                <p>
                    {{ $user->address }}
                </p>
            </div>
        </div>
        <div class="">
            <a href="{{ route('logout') }}"onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
                class="btn btn-danger w-100"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </div>
        <div class="contant-section">
            <div class="card">
                <div class="card-body" style="overflow-x: scroll;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 input-group input-group-icon">
                                <span class="input-group-text">
                                    <div class="input-icon">
                                        <i class="fa-solid fa-calendar"></i>
                                    </div>
                                </span>
                                <input type="date" class="form-control" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 input-group input-group-icon">
                                <span class="input-group-text">
                                    <div class="input-icon">
                                        <i class="fa-solid fa-calendar"></i>
                                    </div>
                                </span>
                                <input type="date" class="form-control" id="end_date">
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <button type="button" class="btn btn-primary w-40" id="filter" style="float: left; margin-right:5px"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                            <button type="button" class="btn btn-danger w-40" id="export"><i class="fa fa-file-pdf" aria-hidden="true"></i> Export</button>
                    </div>


                    <table class="table table-responsive" id="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">User Update</th>
                                <th scope="col">No Laporan</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Status</th>
                                <th scope="col">Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function () {

    var dataTable = new DataTable('#table', {
                ajax: {
                    url: "{{ route('report.getDataProfile') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val();
                        data.end_date = $('#end_date').val();
                    }
                },
                processing: true,
                serverSide: true,
                lengthChange: false,
                bInfo: false,
                columns: [
                    {
                        data: 'creator_name',
                        name:'user_creator.name'
                    },
                    {
                        data: 'updater_name',
                        name:'user_updater.name'
                    },
                    {
                        data: 'no_laporan',
                    },
                    {
                        data: 'judul',
                    },
                    {
                        data: 'lokasi',
                    },
                    {

                        data: 'tanggal',
                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'status_laporan',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var element = `<a target="_blank" href="{{ asset('storage/photos') }}/${data.photo}">Lihat Gambar</a>`;
                            return element;
                        }
                    },
                ],


                'drawCallback': function() {
                    $('#filter').click(function() {
                        dataTable.ajax.reload();
                    })

                }
            });

            exportPdf()

            function exportPdf() {
                $('#export').on('click', function() {
                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();
                    var user_id ={{ $user->id }};
                    var url = '{{ route('report.export') }}' + '?start_date=' + startDate +
                        '&end_date=' + endDate+
                        '&user_id=' + user_id;
                    window.location.href = url;
                });
            }


});
</script>
@endsection
