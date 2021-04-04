@extends('layouts.app')

@section('header')
    @include ('layouts.header')
@endsection


{{-- @section('head')
    <link rel="stylesheet" href="/css/main.css">
@endsection --}}

@section('content')
    <ais-index
        app-id="{{ config('scout.algolia.id') }}"
        api-key="{{ config('scout.algolia.key') }}"
        index-name="threads"
        query="{{ request('q') }}"
    >
        @include('breadcrumbs')

        <div class="flex py-6">
            <div class="mr-10">
                <div class="widget bg-grey-lightest border p-4">
                    <h4 class="widget-heading">Search</h4>

                    <ais-search-box>
                        <ais-input placeholder="Find a thread..." :autofocus="true" class="w-full p-1 text-xs"></ais-input>
                    </ais-search-box>
                </div>

                <div class="widget bg-grey-lightest border p-4">
                    <h4 class="widget-heading">
                        Filter By Channel
                    </h4>

                    <div class="panel-body text-xs">
                        <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                    </div>
                </div>
            </div>

            <div class="w-3/4">
                <a href="https://www.algolia.com/" target="blank" class="text-xs text-blue-darkest font-bold">Powered by Algolia.</a>
                <ais-results>
                    <template slot-scope="{ result }">
                        <li class="list-reset pb-3">
                            <a :href="result.path" class="text-blue link">
                                <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                            </a>
                        </li>
                    </template>
                </ais-results>
            </div>
        </div>
    </ais-index>
@endsection
