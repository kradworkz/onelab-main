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

    <link rel="shortcut icon" href="{{asset('assets/backend/images/icon.png')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('css/backend.css')}}" />
</head>

    <body data-topbar="dark" data-layout="horizontal">
        <div id="app"> 

            <div id="layout-wrapper">
                @include('layouts.backend.header')
                @include('layouts.backend.navigation')

                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            @yield('content')
                        </div>
                    </div>
                    @include('layouts.backend.footer')
                </div>
            </div>

            @include('layouts.backend.rightbar')

            <div class="rightbar-overlay"></div>

        </div>

        <script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('js/backend.js')}}"></script>
    </body>
</html>
