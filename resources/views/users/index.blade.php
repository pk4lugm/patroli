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
    <div class="container ">
        <div class="card">
            <div class="card-body" style="overflow-x: scroll;">
                <table class="table table-striped table-sm" id="table">
                    <thead style="background-color: gray">
                        <tr>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Role</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button class="btn-close" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.users.edit') }}" method="post" enctype="multipart/form-data"
                        id="edit-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ old('id') }}">
                        @error('name')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="name" type="text" class="form-control" placeholder="Nama"
                                value="{{ old('name') }}">
                        </div>

                        @error('email')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="email" type="text" class="form-control" placeholder="Email"
                                value="{{ old('email') }}">
                        </div>

                        @error('phone')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="phone" type="text" class="form-control" placeholder="phone"
                                value="{{ old('phone') }}">
                        </div>

                        @error('level_user')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <select class="form-select " name="level_user">
                                <option value="1" {{ old('level_user') == '1' ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ old('level_user') == '2' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        @error('is_active')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <select class="form-select " name="is_active">
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                            </select>
                        </div>

                        @error('photo')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-radius">
                            <input name="photo" type="file" class="form-control">
                        </div>

                        @error('address')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="input-group mb-3 input-radius">
                            <textarea name="address" class="form-control" placeholder="Alamat" rows="4">{{ old('address') }}</textarea>
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
    <button style="display: none" data-bs-toggle="modal" data-bs-target="#edit-modal" id="dumy">dumy</button>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            new DataTable('#table', {
                ajax: "{{ route('users.get') }}",
                processing: true,
                serverSide: true,
                lengthChange: false,
                bInfo: false,
                // searching: false,
                columns: [{
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'phone',
                    },

                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            if (data.level_user == 1) {
                                return 'admin';
                            } else if (data.level_user == 2) {
                                return 'user';
                            } else {
                                return '';
                            }

                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<img src="{{ asset('storage/profiles') }}/${data.photo}" alt="" style="width:70px">`;

                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            var status = 'nonaktif';
                            if (data.is_active == 1) {
                                status = 'aktif';
                            }
                            return status;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            var element = `
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm edit-button mx-1" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#edit-modal" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    <button class="btn btn-danger btn-sm delete-button mx-1" data-id="${data.id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>
            `;
                            return element;
                        }
                    },

                ],


                'drawCallback': function() {
                    edit();
                    deleteData()

                }
            });

            function edit() {
                $('.edit-button').click(function(e) {
                    var id = $(this).attr('data-id');
                    $.ajax({
                            url: '{{ route('admin.users.detail') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id
                            },
                        })
                        .done(function(data) {
                            $('#edit-form [name="id"]').val(data.id)
                            $('#edit-form [name="name"]').val(data.name)
                            $('#edit-form [name="email"]').val(data.email)
                            $('#edit-form [name="level_user"]').val(data.level_user)
                            $('#edit-form [name="is_active"]').val(data.is_active)
                            $('#edit-form [name="address"]').val(data.address)
                            $('#edit-form [name="phone"]').val(data.phone)
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
                                "{{ route('admin.users.delete', ['id' => '']) }}" + id;
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
@endpush
