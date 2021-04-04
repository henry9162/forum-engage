<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        window.auth = {!!auth()->user()!!}
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NYSC Engage') }}</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/header.css">
    
    <link rel="manifest" href="/manifest">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};   
    </script>

    <script src='https://www.google.com/recaptcha/api.js'></script>

    <style>
        .nav-image {
            margin-top: 0px;
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1400px;
            }
         }
         @media (min-width: 1366px) {
            .container {
                max-width: 2200px;
            }
         }
    </style>

    @yield('head')
</head>

<body class="font-sans bg-grey-light h-full">
    <div id="app" class="flex flex-col min-h-full">
        <div class="container md:mb-5 flex flex-col">
            @include ('layouts.header')
            @include ('layouts.nav')
        </div>

        <div class="container">
            <div class="flex-col md:flex flex-1">
                @section('chatsbar')
                    @include('chatsbar')
                @show
            </div>
        </div>

        {{-- footer section --}}
        <div class="p-8 mt-1 flex-grow md:mt-8 text-xs bg-grey-darkest text-center">
            <span class="mr-2"><a href="#" class="text-grey-lightest font-bold">&copy; 2019 Sidmach Technologies</a></span>
            {{-- <span class="mr-2"><a href="#" class="text-grey-lightest">About</a></span>
            <span class="mr-2"><a href="#" class="text-grey-lightest">Help Center</a></span>
            <span class="mr-2"><a href="#" class="text-grey-lightest">Terms</a></span>
            <span class="mr-2"><a href="#" class="text-grey-lightest">Privacy policy</a></span>
            <span class="mr-2"><a href="#" class="text-grey-lightest">Cookies</a></span>
            <span class="mr-2"><a href="#" class="text-grey-lightest">Ads info</a></span> --}}
        </div>

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
