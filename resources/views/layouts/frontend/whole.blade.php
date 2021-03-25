<!doctype html>
<html lang="en">

<head>
    <title>{{config('app.name')}}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Krad was here" name="description" />
    <meta content="KradWasHere" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

    <link rel="shortcut icon" href="{{asset('assets/backend/images/icon.png')}}" async>
    <link type="text/css" rel="stylesheet" href="{{asset('css/backend.css')}}" async/>
</head>

    <body data-topbar="dark" data-layout="horizontal">
        <div id="app"> 
            @include('layouts.frontend.header')
                @yield('content')
            @include('layouts.frontend.footer')
        </div>

        <script src="{{asset('js/app.js')}}" async></script>
        <script src="{{asset('js/frontend.js')}}" async></script>
    </body>
</html>
