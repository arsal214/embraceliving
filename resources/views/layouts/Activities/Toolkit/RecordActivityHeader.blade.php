<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>MagicMoments</title>
    <!-- Dropdown Testing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css"
        integrity="sha512-IuO+tczf4J43RzbCMEFggCWW5JuX78IrCJRFFBoQEXNvGI6gkUw4OjuwMidiS4Lm9Q2lILzpJwZuMWuSEeT9UQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/2.0.3/styles/overlayscrollbars.min.css"
        integrity="sha512-9KIEj/9zfBvTzT88vrg39lGzrTdZMsUQp2MCw/QNL7Ckf0xDtHeYHoXhE8dNN0d1uQMVgBoUPTzzX+NIt3hRgA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css')}}">
    <link rel="stylesheet" href="{{ asset('css/all.css')}}">

</head>

<body>
    <div class="mma-container">
        <div class="mma-header">
            <nav class="navbar navbar-expand-sm navbar">
                <!-- Check Roles -->
                <a class="navbar-brand"><img src="{{ asset('images/mma_logo_full.png') }}" alt="logo" width="340"
                        height="60" /></a>
                <button class="navbar-toggler" type="button">
                    <i class="fas fa-bars"> </i>
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample07">
                    <ul class="nav navbar-nav mma_head">
                        <li class="nav-item" id="ar_create_custom"> <a href="#">
                                <img src="{{ asset('images/new.png') }}" alt="" width="80" height="60"><br>
                                Custom </a>
                        </li>
                        <li class="nav-item" id="ar_reset_record"> <a href="#">
                                <img src="{{ asset('images/reset.png') }}" alt="" width="80" height="60"><br>
                                Reset </a>
                        </li>
                        <li class="nav-item" id="ar_proceed_activity">
                            <a href="javascript:void(0)">
                                <img src="{{ asset('images/proceed.png') }}" alt="" width="80" height="60"><br>
                                Proceed
                            </a>
                        </li>
                        <li class="nav-item" id="ar_submit_activity" style="display:none;">
                            <a href="javascript:void(0)">
                                <img src="{{ asset('images/submit.png') }}" alt="" width="80" height="60"><br>
                                Submit
                            </a>
                        </li>
                        <li class="nav-item" id="mma_header_back"> <a href="/admin/ActivityReporting" class="hBack"> <img
                                    src="{{ asset('images/back_but.png') }}" alt="" width="80" height="60"><br>
                                Back </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ./ Header Ends -->