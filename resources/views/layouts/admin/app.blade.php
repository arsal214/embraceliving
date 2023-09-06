<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.admin.header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
@include('layouts.admin.navbar')
@include('layouts.admin.aside')
@yield('content')
@include('layouts.admin.footer')
@include('layouts.admin.script')
</div>
</body>
</html>
