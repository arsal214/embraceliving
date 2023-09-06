@php
    $groups =  auth()->user()->group;
@endphp
<footer class="main-footer mt-3">
    {{$groups->footer ?? 'Copyright 2023 All rights reserved.'}}
</footer>

