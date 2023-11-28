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
    <div class="container">

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
                    <div class="col-md-4">
                        <div class="mb-3 input-group input-radius">
                                <select class="mb-3" name="user_id" id="user_id" multiple="multiple">
                                        @foreach ($users as $user)
                                        <option value="{{ encrypt($user->id) }}">{{ $user->name }}</option>
                                        @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-md-4" >
                        <button type="button" class="btn btn-primary w-40" id="filter" style="float: left; margin-right:5px"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                        <button type="button" class="btn btn-danger w-40" id="export"><i class="fa fa-file-pdf" aria-hidden="true"></i> Export</button>
                    </div>
                </div>


                <table class="table table-responsive" id="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">User Update</th>
                            <th scope="col">No Laporan</th>
                            <th scope="col">Judul</th>
                            <th scope="col">lokasi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Laporan</h5>
                    <button class="btn-close" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.report.edit') }}" method="post" enctype="multipart/form-data"
                        id="edit-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ old('id') }}">
                        @error('judul')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="judul" type="text" class="form-control" placeholder="Judul"
                                value="{{ old('judul') }}">
                        </div>

                        @error('phone')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="phone" type="text" class="form-control" placeholder="phone"
                                value="{{ old('phone') }}">
                        </div>

                        @error('photo')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="photo" type="file" class="form-control">
                        </div>

                        @error('status_laporan')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <select class="form-select " name="status_laporan">
                                <option value="request" {{ old('status_laporan') == 'request' ? 'selected' : '' }}>request
                                </option>
                                <option value="accept" {{ old('status_laporan') == 'accept' ? 'selected' : '' }}>accept
                                </option>
                            </select>
                        </div>

                        @error('deskripsi')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="input-group mb-3 input-radius">
                            <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" rows="4">{{ old('deskripsi') }}</textarea>
                        </div>

                        <button class="btn btn-primary mt-3 btn-block">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <button style="display: none" data-bs-toggle="modal" data-bs-target="#edit-modal" id="dumy">dumy</button>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#user_id').select2();

            var dataTable = new DataTable('#table', {
                ajax: {
                    url: "{{ route('admin.report.get') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val();
                        data.end_date = $('#end_date').val();
                        data.user_id = $('#user_id').val();
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
                    {
                        data: null,
                        render: function(data, type, row) {
                            var element = `
                            <div class="d-flex justify-content-center">
                                <a href="${data.link}" class="btn btn-success btn-sm mx-1" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <button class="btn btn-primary btn-sm edit-button mx-1" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#edit-modal" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                <button class="btn btn-danger btn-sm delete-button mx-1" data-id="${data.id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </div>
                        `;
                            return element;
                        }
                    }
                ],


                'drawCallback': function() {
                    edit();
                    deleteData()
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
                    var user_id = JSON.stringify($('#user_id').val());
                    var url = '{{ route('admin.report.export') }}' + '?start_date=' + startDate +
                        '&end_date=' + endDate+
                        '&user_id=' + user_id;
                    window.location.href = url;
                });
            }

            function edit() {
                $('.edit-button').click(function(e) {
                    var id = $(this).attr('data-id');
                    $.ajax({
                            url: '{{ route('admin.report.detail') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id
                            },
                        })
                        .done(function(data) {
                            $('#edit-form [name="judul"]').val(data.judul)
                            $('#edit-form [name="phone"]').val(data.phone)
                            $('#edit-form [name="deskripsi"]').val(data.deskripsi)
                            $('#edit-form [name="status_laporan"]').val(data.status_laporan)
                            $('#edit-form [name="id"]').val(data.id)
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        })


                });

            }

            function deleteData() {

                $('.delete-button').click(function() {
                    var id = $(this).attr('data-id');
                    Swal.fire({
                        title: 'Yakin ingin di hapus?',
                        text: "setelah di hapus data tidak bisa di kembalikan",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                '{{ route('admin.report.delete', ['id' => '']) }}' + id;
                        }
                    })

                })
            }


        });
    </script>
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#dumy').click()
            });
        </script>
    @endif
@endsection
