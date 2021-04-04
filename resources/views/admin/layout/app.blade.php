@extends('layouts.app')

@section('header')
    @include ('layouts.header')
@endsection

{{-- @section('head')
    <link rel="stylesheet" href="/css/main.css">
@endsection --}}

@section('sidebar')
    <aside class="border-l border-r w-56">
        <div class="widget bg-white p-6 ml-4 mt-6 rounded">
            <h4 class="widget-heading">Forum Management</h4>

            <ul class="list-reset text-sm">
                <li class="pb-3 text-xs">
                    <a href="{{ route('admin.dashboard.index') }}" class="{{ Route::is('admin.dashboard.index') ? 'text-blue font-bold' : '' }}">Dashboard</a>
                </li>

                <li class="pb-3 text-xs">
                    <a href="{{ route('admin.channels.index') }}" class="{{ Route::is('admin.channels.index') ? 'text-blue font-bold' : '' }}">Channels</a>
                </li>
            </ul>
        </div>

        <div class="mt-6 ml-4 flex-shrink">
            <advert-carousel></advert-carousel>
        </div>
    </aside>
@endsection

@section('content')
    <div class="py-6">
        @yield('administration-content')
    </div>
@endsection
