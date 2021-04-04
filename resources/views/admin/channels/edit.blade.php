@extends('admin.layout.app')

@section('administration-content')
<div class="container">
        <div class="flex justify-center px-16 my-6">
            <form class="rounded bg-grey-light w-full px-10 py-8 shadow" method="POST" action="{{ route('admin.channels.update', ['channel' => $channel->slug]) }}">
                {{ method_field('PATCH') }}
                @include ('admin.channels._form', ['buttonText' => 'Update Channel'])
            </form>
        </div>
    </div>
@endsection
