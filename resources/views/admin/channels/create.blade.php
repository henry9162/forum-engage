@extends('admin.layout.app')

@section('administration-content')
    <div class="container">
        <div class="flex justify-center px-16 my-6">
            <form class="rounded bg-grey-light w-full px-10 py-8 shadow" method="POST" action="{{ route('admin.channels.store') }}">
                @include ('admin.channels._form')
            </form>
        </div>  
    </div>
@endsection
