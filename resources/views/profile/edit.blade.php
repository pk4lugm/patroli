

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
					<a href="{{ route('profile') }}" class="text-black">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
					</a>
					<h4 class="title mb-0">Edit profile</h4>
				</div>
				<div class="mid-content">
				</div>
			</div>
		</div>
	</header>
    <!-- Header End -->

    <!-- Page Content -->
    <div class="page-content">
        <form action="{{ route('profile.save') }}" method="post" enctype="multipart/form-data" id="form">
            @csrf
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
                        {{-- <label for=""> ubah foto Profile</label> --}}
                        <div>
                            @error('photo')
                                <div class="alert text-danger mb-0">{{ $message }}</div>
                             @enderror
                            <input type="file" name="photo">
                        </div>
                    </div>
                        @error('name')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-mini">
                            <input name="name" type="text" class="form-control" placeholder="Nama" value="{{ $user->name }}">
                        </div>
                        @error('password')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-mini">
                            <input name="password" type="password" class="form-control" placeholder="Password">
                        </div>
                        @error('phone')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-mini">
                            <input name="phone" type="number" class="form-control" placeholder="Nomor Handphone" value="{{ $user->phone }}">
                        </div>
                        @error('address')
                            <div class="alert text-danger mb-0">{{ $message }}</div>
                        @enderror
                        <div class="mb-3 input-group input-mini">
                            <input name="address" type="text" class="form-control" placeholder="Alamat" value="{{ $user->address }}">
                        </div>
                </div>
            </div>
        </form>
        <!-- Tombol Submit -->
        <div class="fixed-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100" id="submit">Submit</button>
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
@include('sweetalert::alert')
<script>
    $(document).ready(function () {
        $('#submit').click(function (e) {
            $('#form').submit();
        });
    });
</script>
<!-- Mirrored from soziety.dexignzone.com/xhtml/edit-profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 Aug 2023 02:37:20 GMT -->
</html>
