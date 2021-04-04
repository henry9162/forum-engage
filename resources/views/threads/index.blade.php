@extends('layouts.app')

@section('header')
    @include ('layouts.header')
@endsection

@section('content')
    <div class="flex justify-between">
        <div>
            @include('breadcrumbs')
        </div>

        <div class="widget border-b-0 mt-4 md:hidden">
            @if (auth()->check())   
                <button class="btn is-green w-full" @click="$modal.show('new-thread')">Add New Thread</button> 
            @else
                <button class="btn is-green w-full tracking-wide" @click="$modal.show('login')">Log In To Post</button>
            @endif
        </div>
    </div>

    <div class="pt-3 pb-8">
        @include ('threads._list')

        {{ $threads->render() }}
    </div>
@endsection
