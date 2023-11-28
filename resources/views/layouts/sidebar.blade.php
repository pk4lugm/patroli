<div class="sidebar">
    <div class="author-box">
        <div class="dz-media">
            @if (empty(dataUser()->photo))
                <img src="{{ asset('template/assets/images/stories/small/pic4.jpg') }}" alt="author-image">
            @else
                <img src="{{ asset('storage/profiles/' . dataUser()->photo) }}" alt="author-image">
            @endif

        </div>
        <div class="dz-info">
            <span>Selamat datang</span>
            <h5 class="name">{{ dataUser()->name }}</h5>
        </div>
    </div>
    <ul class="nav navbar-nav">
        @if (dataUser()->level_user == 1)
            <li><a class="nav-link" href="{{ route('admin.report') }}">
                    <span class="dz-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                            fill="#000000">
                            <path
                                d="M12.6 18.06c-.36.28-.87.28-1.23 0l-6.15-4.78c-.36-.28-.86-.28-1.22 0-.51.4-.51 1.17 0 1.57l6.76 5.26c.72.56 1.73.56 2.46 0l6.76-5.26c.51-.4.51-1.17 0-1.57l-.01-.01c-.36-.28-.86-.28-1.22 0l-6.15 4.79zm.63-3.02l6.76-5.26c.51-.4.51-1.18 0-1.58l-6.76-5.26c-.72-.56-1.73-.56-2.46 0L4.01 8.21c-.51.4-.51 1.18 0 1.58l6.76 5.26c.72.56 1.74.56 2.46-.01z" />
                        </svg>
                    </span>
                    <span>Laporan</span>
                </a></li>
            <li><a class="nav-link" href="{{ route('admin.patrol') }}">
                    <span class="dz-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                            fill="#000000">
                            <path
                                d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                        </svg>
                    </span>
                    <span>Jadwal Patroli</span>
                </a></li>
            <li><a class="nav-link" href="{{ route('admin.users') }}">
                    <span class="dz-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"
                            fill="#000000">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v1c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-1c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </span>
                    <span>User</span>
                </a></li>
        @endif
        <li class="nav-color" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
            aria-controls="offcanvasBottom">
            <a href="javascript:void(0);" class="nav-link">
                <span class="dz-icon">
                    <svg class="color-plate" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24"
                        width="30px" fill="#000000">
                        <path
                            d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                    </svg>
                </span>
                <span>Tema Warna</span>
            </a>
        </li>
    </ul>
</div>
