

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from soziety.dexignzone.com/xhtml/edit-profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 Aug 2023 02:37:20 GMT -->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
	<meta name="theme-color" content="#2196f3">
	<meta name="author" content="DexignZone" />
    <meta name="keywords" content="" />
    <meta name="robots" content="" />
	<meta name="description" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )"/>
	<meta property="og:title" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )" />
	<meta property="og:description" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )" />
	<meta property="og:image" content="error.html"/>
	<meta name="format-detection" content="telephone=no">

	<!-- Favicons Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.png" />

    <!-- Title -->
	<title>Profile</title>

    <!-- Stylesheets -->
	<!-- Stylesheets -->
    <link href="{{ asset('template') }}/assets/vendor/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/vendor/lightgallery/dist/css/lg-thumbnail.css" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/vendor/lightgallery/dist/css/lg-zoom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('template') }}/assets/css/style.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&amp;family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
</head>
</head>
<body>
<div class="page-wraper header-fixed">

    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Preloader end-->

	<!-- Header -->
	<header class="header bg-white">
		<div class="container">
			<div class="main-bar">
				<div class="left-content">
					<a href="{{ route('home') }}" class="text-black">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
					</a>
					<h4 class="title mb-0">Profile</h4>
				</div>
				<div class="mid-content">
				</div>
			</div>
		</div>
	</header>
    <!-- Header End -->
    <!-- Page Content -->
    <div class="page-content">
            <div class="container">
                <div class="edit-profile">
                    <div class="profile-image">
                        <div class="media media-100 rounded-circle">
                            @if (empty($user->photo))
                                <img src="{{ asset('template') }}/assets/images/stories/small/pic4.jpg" alt="{{ $user->name }}">
                            @else
                                <img src="{{ asset('storage/profiles/'.$user->photo) }}" alt="{{ $user->name }}">
                            @endif
                        </div>

                    </div>
                        <label for="">Email</label>
                        <div class="mb-3 input-group input-mini">
                            <input readonly type="text" class="form-control" value="{{ $user->email }}">
                        </div>

                        <label for="">Nama</label>
                        <div class="mb-3 input-group input-mini">
                            <input readonly type="text" class="form-control" placeholder="Nama" value="{{ $user->name }}">
                        </div>

                        <label for="">No Hp</label>
                        <div class="mb-3 input-group input-mini">
                            <input readonly type="number" class="form-control" value="{{ $user->phone }}">
                        </div>

                        <label for="">Alamat</label>
                        <div class="mb-3 input-group input-mini">
                            <input readonly type="text" class="form-control" placeholder="Alamat" value="{{ $user->address }}">
                        </div>
                </div>
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
                            {{-- <div class="col-md-2">

                            </div> --}}
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
    <!-- Page Content End-->
</div>
<!--**********************************
    Scripts
***********************************-->
<script src="{{ asset('template') }}/assets/js/jquery.js"></script>
<script src="{{ asset('template') }}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('template') }}/assets/vendor/lightgallery/dist/lightgallery.umd.js"></script>
<script src="{{ asset('template') }}/assets/vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.umd.js"></script>
<script src="{{ asset('template') }}/assets/vendor/lightgallery/dist/plugins/zoom/lg-zoom.umd.js"></script>
<script src="{{ asset('template') }}/assets/js/settings.js"></script>
<script src="{{ asset('template') }}/assets/js/custom.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('sweetalert::alert')
<script>
    $(document).ready(function () {
        $('#submit').click(function (e) {
            $('#form').submit();
        });

        var dataTable = new DataTable('#table', {
                ajax: {
                    url: "{{ route('report.getDataUser') }}",
                    data: function(data) {
                        data.start_date = $('#start_date').val();
                        data.end_date = $('#end_date').val();
                        data.user_id = "{{ encrypt($user->id) }}"
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
</html>
