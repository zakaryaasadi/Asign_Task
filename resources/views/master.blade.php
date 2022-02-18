<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kiswa Reports</title>
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

        @yield('vendor.css')
        <link rel="stylesheet" href="{{asset('app-assets/css-rtl/custom-rtl.min.css')}}">
        <link rel="stylesheet" href="{{asset('app-assets/css-rtl/themes/semi-dark-layout.min.css')}}">
        <link rel="stylesheet" href="{{asset('app-assets/css-rtl/themes/dark-layout.min.css')}}">

        <link rel="stylesheet" href="{{asset('app-assets/css-rtl/colors.min.css')}}">
        <link rel="stylesheet" href="{{asset('app-assets/css-rtl/bootstrap-extended.min.css')}}">
        <link rel="stylesheet" href="{{asset('app-assets/css-rtl/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/style-rtl.css')}}">

        @yield('page.css')

  
    </head>
    <body>
        <div class="content-wrapper" style="margin-left: 0px">
            @yield('content')
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

        @yield('js')
        </body>
    </html>