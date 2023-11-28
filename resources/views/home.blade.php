@extends('layouts.app')

@section('content')
<style>
    .detail.text-container {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div class="content-inner pt-0">
    <div class="container p-b50">

        <div class="author-notification mb-4">
            <div class="swiper-btn-center-lr my-0">
                <div class="swiper-container categorie-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($users as $user)
                        <div class="swiper-slide">
                            <a href="{{ route('profile.detail', ['id' => encrypt($user->id)]) }}" class="categore-box">
                                <div class="story-bx">
                                    @if (empty($user->photo))
                                    <img src=" {{ asset('template') }}/assets/images/stories/small/pic4.jpg" alt="/">
                                    @else
                                    <img src="{{ asset('storage/profiles/' . $user->photo) }}" alt="/">
                                    @endif
                                </div>
                                <span class="detail text-container">{{ substr(explode(' ', $user->name)[0], 0, 13) }}</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- STORY -->

        <form action="{{ route('home') }}" method="get">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="mb-3 input-group input-group-icon">
                        <span class="input-group-text">
                            <div class="input-icon">
                                <i class="fa-solid fa-calendar"></i>
                            </div>
                        </span>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 input-group input-group-icon">
                        <span class="input-group-text">
                            <div class="input-icon">
                                <i class="fa-solid fa-calendar"></i>
                            </div>
                        </span>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="mb-3" name="no_laporan">
                        <option value=""></option>
                        @foreach ($no_laporans as $no_laporan)
                        <option {{ request('no_laporan') == $no_laporan->no_laporan ? 'selected' : '' }}>
                            {{ $no_laporan->no_laporan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-40" id="filter" style="float: left; margin-right:5px"><i class="fa fa-filter" aria-hidden="true"></i>
                        Filter</button>
                </div>

            </div>
        </form>
        <div class="post-area">

            @foreach ($reports as $report)

            <div class="card">
                <div class="card-body" style="padding-bottom: 0px">
                    <div class="post-card">
                        <div class="top-meta">
                            <div class="d-flex justify-content-between align-items-start">
                                <a href="javascript:void(0);" class="media media-40">
                                    @if (empty($report->user_photo))
                                    <img class="rounded" src="{{ asset('template') }}/assets/images/stories/small/pic4.jpg" alt="/">
                                    @else
                                    <img class="rounded" src="{{ asset('storage/profiles/' . $report->user_photo) }}" alt="/">
                                    @endif
                                </a>
                                <div class="meta-content ms-3">
                                    <h6 class="title mb-0"><a href="javascript:void(0);">{{ $report->user_name }}</a></h6>
                                    <ul class="meta-list" style="padding-left: 0px">
                                        <li>
                                            @if ($report->status_laporan == 'request')
                                            <span class="badge bg-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Belum di proses</span>
                                            @else
                                            <span class="badge bg-success"><i class="fa fa-check" aria-hidden="true"></i> Sudah di proses</span>
                                            @endif
                                        </li>
                                        <li class="title">
                                            {{ \Carbon\Carbon::parse($report->tanggal)->diffForHumans() }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <p style="margin-bottom: 10px" class="title"><b><i class="fa fa-info-circle" aria-hidden="true"></i>
                                {{ $report->no_laporan }} - @if ($report->phone)
                                <?php
                                // Remove the first character if it's '0', and prepend with '+62'
                                $phoneNumber = ltrim($report->phone, '0');
                                $waNumber = '+62' . $phoneNumber;
                                ?>
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank">{{ $waNumber }}</a>
                                @endif
                            </b></p>
                        <p style="text-align: justify" class="title">
                            <b>{{ $report->judul}} - {{ $report->lokasi }}</b>
                        </p>
                        <p>
                            {{ $report->deskripsi }}
                        </p>
                        </p>
                        <div class="dz-media">
                            <img src="{{ asset('storage/photos/' . $report->photo) }}" alt="/" style="width: 100%">
                            <div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="btn-group" style="float: left">
                                            <a href="{{ route('comment', ['id' => encrypt($report->id)]) }}" class="btn btn-primary" autocomplete="off" style="width: 80px;text-align:left "><i class="fa fa-comment" aria-hidden="true"></i> {{ $report->comment_count }}</a>
                                        </div>&nbsp;
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Update
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item request" href="javascript:void(0);" data-id="{{ encrypt($report->id) }}">Belum di proses</a></li>
                                                <li><a class="dropdown-item accept" href="javascript:void(0);" data-id="{{ encrypt($report->id) }}">Sudah di proses</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            @endforeach


        </div>
        <div class="d-flex justify-content-center">
            {{ $reports->links() }}
        </div>




    </div>
</div>


<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('.accept').click(function(e) {
            var id = $(this).attr('data-id');
            var card = $(this).closest('.card-body');
            console.log(card)
            card.find('.badge').html(`<i class="fa fa-check" aria-hidden="true"></i> Sudah di proses`);
            card.find('.badge').removeClass('bg-secondary');
            card.find('.badge').addClass('bg-success');

            $.ajax({
                url: '{{ route("report.update") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: 'accept'
                },
            })
        });
        $('.request').click(function(e) {
            var id = $(this).attr('data-id');
            var card = $(this).closest('.card-body');
            card.find('.badge').html(`<i class="fa fa-refres" aria-hidden="true"></i> Belum di proses`);
            card.find('.badge').removeClass('bg-success');
            card.find('.badge').addClass('bg-secondary');

            $.ajax({
                url: '{{ route("report.update") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    status: 'request'
                },
            })
        });



    });
</script>



@push('js')
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
@endpush
@endsection