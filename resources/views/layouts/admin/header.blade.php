 <meta charset="utf-8" />
 @php
     $groupsData = auth()->user()->group; //for groups table only
     $groups = getThemeData();
 @endphp
 <title>
     @if (trim($__env->yieldContent('title')))
         @yield('title') |
     @endif {{ $groups->name ?? config('app.name', 'Laravel') }}
 </title>
  <!-- Styles -->
  <link rel="icon" type="image/x-icon" href=" {{$groupsData->favicon ?? '/images/small_logo.png'}}" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
  <meta name="csrf-token" content="3D1JM3mnnIBkMhO6Tj4nL6XkfUTBVT52y2TH7CUS" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
    integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
    crossorigin="anonymous" />
    <!-- \Dropdown Testing -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<!-- \Dropdown Testing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css" integrity="sha512-IuO+tczf4J43RzbCMEFggCWW5JuX78IrCJRFFBoQEXNvGI6gkUw4OjuwMidiS4Lm9Q2lILzpJwZuMWuSEeT9UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/2.0.3/styles/overlayscrollbars.min.css" integrity="sha512-9KIEj/9zfBvTzT88vrg39lGzrTdZMsUQp2MCw/QNL7Ckf0xDtHeYHoXhE8dNN0d1uQMVgBoUPTzzX+NIt3hRgA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- AdminLTE App -->
 <link rel="stylesheet" href="{{ asset('theme/select2/select2.css') }}" />
{{-- <link rel="stylesheet" href="{{ asset('theme/toastr/toastr.css') }}" />--}}

  <!-- Custom styles for this template -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
 <link rel="stylesheet" href="{{ asset('theme/dropify/dist/css/dropify.min.css')}}">

 @yield('page-head')


