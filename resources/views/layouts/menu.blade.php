@php
    use Illuminate\Support\Facades\Route;
    $thiRute = Route::currentRouteName();
@endphp
<div class="menubar-area">
    <div class="toolbar-inner menubar-nav">
        <a href="{{ route('home') }}" class="nav-link {{ $thiRute == 'home' ? 'active' : '' }}">
            <i class="fa fa-home fa-lg" aria-hidden="true" style="color: gray"></i>
        </a>
        <a href="{{ route('patrol') }}" class="nav-link {{ $thiRute == 'patrol' ? 'active' : '' }}">
            <i class="fa fa-calendar fa-lg" aria-hidden="true" style="color: gray"></i>
        </a>
        <a href="{{ route('report') }}" class="nav-link add-post">
            <i class="fa-solid fa-plus fa-lg"></i>
        </a>
        <a href="{{ route('report.user') }}" class="nav-link {{ $thiRute == 'report.user' ? 'active' : '' }}">
            <i class="fa fa-file fa-lg" aria-hidden="true" style="color: gray"></i>
        </a>
        <a href="{{ route('profile') }}" class="nav-link {{ $thiRute == 'profile' ? 'active' : '' }}">
            <i class="fa fa-user fa-lg" aria-hidden="true" style="color: gray"></i>
        </a>
    </div>
</div>
