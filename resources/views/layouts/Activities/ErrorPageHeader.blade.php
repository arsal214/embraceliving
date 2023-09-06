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

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css')}}">
    <link rel="stylesheet" href="{{ asset('css/all.css')}}">
</head>
</head>
<body>
<div class="mma-container">
    <div class="mma-header">
      <nav class="navbar navbar-expand-sm navbar">
        <!-- Check Theme -->
                <a class="navbar-brand"><img src="{{ asset('images/mma_logo_full.png') }}" alt="logo" width="340" height="60" /></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
          <!--<span class="navbar-toggler-icon"></span>-->
          <i class="fas fa-bars"> </i>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample07">
          <ul class="nav navbar-nav mma_head">
            <li class="nav-item" style="display:none;">
              <div class="header_brand"></div>
            </li>
                      </ul>
        </div>
      </nav>
    </div>