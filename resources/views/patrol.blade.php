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
                        <button type="button" class="btn btn-danger w-40" id="export" style="display: none"><i class="fa fa-file-pdf" aria-hidden="true"></i> Export</button>
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
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
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
                ],
                searchBuilder: {
                        columns: [0] // Indeks kolom 'user_name'
                    },

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
                    var url = '{{ route('admin.patrol.export') }}' + '?start_date=' + startDate +
                        '&end_date=' + endDate;
                    window.location.href = url;
                });
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
