@extends('layouts.app')

{{-- @section('head')
    <link rel="stylesheet" href="/css/main.css">
@endsection --}}

@section('header')
    @include ('layouts.header')
@endsection

@section('content')
    @if(count($profileUser->pictures))
        @foreach($profileUser->pictures as $picture)
            <avatar-form :user="{{ $profileUser }}" :image="{{ $picture }}"></avatar-form>
        @endforeach
    @else
        <avatar-form :user="{{ $profileUser }}"></avatar-form>
    @endif

    @if(auth()->user()->isAdmin())
        <activities :user="{{ $profileUser }}"></activities>
    @endif
@endsection
