@extends('layouts.app')

{{-- @section('head')
    <link rel="stylesheet" href="/css/main.css">
@endsection --}}

@section('header')
    @include ('layouts.header')
@endsection

@section('content')
    <div class="my-10"><h1>Leaderboard</h1></div>
    <leaderboard></leaderboard>
@endsection
