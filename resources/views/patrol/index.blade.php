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
                <div class="row mb-3">
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
                    
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success w-40" id="add" style="float: left; margin-right:5px"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
                    </div>
                </div>


                <table class="table table-responsive" id="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Staff</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jam Mulai</th>
                            <th scope="col">Jam Selesai</th>
                            <th scope="col">Penugasan</th>
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
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Patroli</h5>
                    <button class="btn-close" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ empty(old('id'))?route('admin.patrol.add'):route('admin.patrol.edit')   }}  " method="post" enctype="multipart/form-data" id="form">
                        @csrf
                        <input type="hidden" name="id" value="{{ old('id') }}">
                        @error('userid')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <select class="mb-3" name="userid">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('tanggal')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                          <div class="mb-3 input-group input-group-icon">
                            <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}">
                        </div>


                        @error('jam_awal')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="jam_awal" type="time" class="form-control" placeholder="Jam Mulai"
                                value="{{ old('jam_awal') }}">
                        </div>

                        @error('jam_akhir')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="jam_akhir" type="time" class="form-control" placeholder="Jam Selesai"
                                value="{{ old('jam_akhir') }}">
                        </div>

                        @error('penugasan')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <select class="mb-3" name="penugasan">
                                    <option value="patroli">Patroli</option>
                                    <option value="jaga">Jaga</option>
                            </select>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <button style="display: none" data-bs-toggle="modal" data-bs-target="#modal" id="dumy">dumy</button>
    <script>
        $(document).ready(function() {
            // $('.js-example-basic-single').select2();
            $('#add').click(function(){
                var form = $("#form");
                form.attr("action", "{{ route('admin.patrol.add') }}");
                $('#title_model').text('Tambah Item');
                $('#form input').not('[name="_token"]').val('');
                $('#form textarea').val('');
                $('#dumy').click();
            })


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            var dataTable = new DataTable('#table', {
                ajax: {
                    url: "{{ route('admin.patrol.get') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val();
                        data.end_date = $('#end_date').val();
                    }
                },
                "language": {
                    "emptyTable": "Tidak ada data Patroli"
                },
                processing: true,
                serverSide: true,
                lengthChange: false,
                bInfo: false,
                columns: [
                    { data: 'name', name: 'users.name' }, // Gunakan alias 'user_name',
                    {
                        data: 'tanggal',
                    },
                    {

                        data: 'jam_awal',
                    },
                    {
                        data: 'jam_akhir',
                    },
                    {
                        data: 'penugasan',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var element = `
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm edit-button mx-1" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#edit-modal" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                            <button class="btn btn-danger btn-sm delete-button mx-1" data-id="${data.id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </div>
                    `;
                            return element;
                        }
                    }
                ],
                searchBuilder: {
                        columns: [0] // Indeks kolom 'user_name'
                    },

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
                    var url = '{{ route('admin.patrol.export') }}' + '?start_date=' + startDate +
                        '&end_date=' + endDate;
                    window.location.href = url;
                });
            }

            function edit() {
                $('.edit-button').click(function(e) {
                    var id = $(this).attr('data-id');
                    $.ajax({
                            url: '{{ route('admin.patrol.detail') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id
                            },
                        })
                        .done(function(data) {
                            $('#form').attr('action','{{ route("admin.patrol.edit") }}')
                            $('#form [name="id"]').val(data.id)
                            $('#form [name="userid"]').val(data.user_id)
                            $('#form [name="tanggal"]').val(data.tanggal)
                            $('#form [name="jam_awal"]').val(data.jam_awal)
                            $('#form [name="jam_akhir"]').val(data.jam_akhir)
                            $('#form [name="penugasan"]').val(data.penugasan)
                            $('#dumy').click();
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
                                '{{ route('admin.patrol.delete', ['id' => '']) }}' + id;
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
