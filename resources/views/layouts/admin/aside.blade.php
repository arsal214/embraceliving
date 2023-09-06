<aside class="main-sidebar sidebar-mmt">
    @php
     $groups = auth()->user()->group;
    @endphp
    <div class="logobar">
        <a href="{{url('/')}}" class="logo-link">
            <img src="{{$groups->logo ?? '/images/small_logo.png'}}"   alt="{{$groups->name ?? 'Embrace Living'}}]}" class="img-fluid">
            {{$groups->name ?? 'Embrace Living'}}
        </a>
    </div>

    @include('layouts.admin.sidebar')
</aside>
