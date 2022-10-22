@yield('bra_top')<!DOCTYPE html>
<html style="height: auto">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>@yield('page_title')</title>
</head>
<body class="bra-scroll-bar">

<div class="bra-body bra-wrapper" id="bra_app">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" media="all"/>
    @section('top_header')
            @include('install.style')
    @show
    <div class="bra-body-content clearfix">


            <div class="sub-menu-top">
                @section('table_form')
                @show
            </div>



        @section('tips')
        @show


        @section('main')
        @show
    </div>
</div>

@section('requirejs')
    <script type="text/javascript" src="/statics/js/require.js"></script>
    <script type="text/javascript" src="/statics/js/config.js"></script>
@show

@section('footer_js')
@show

@section('bra_init_js')
    <script>
        require(['braui'], function (braui) {
            braui.bra_init();
        });
    </script>
@show

</body>
</html>
@section('bra_end')
@endsection
