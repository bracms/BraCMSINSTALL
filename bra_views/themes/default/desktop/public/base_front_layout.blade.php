@yield('bra_top')<!DOCTYPE html>
<html style="height: auto">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
    <link type="favicon" rel="shortcut icon" href="/favicon.ico"/>
    <meta name="Generator" content="BraCMS"/>
    <title>{{$seo['seo_title']}}</title>
    <meta name="keywords" content="{{$seo['seo_keywords']}}">
    <meta name="description" content="{{$seo['seo_desc']}}">
    <meta name="baidu-site-verification" content="codeva-tj7UrP0wWl" />


    @section('css_icon')
        <link rel="stylesheet" href="{{asset('statics/css/bra_icon.css')}}">
    @show
    @section('bra_css')
        <link rel="stylesheet" type="text/css" href="{{asset('/statics/packs/bulma/bulma.min.css')}}" media="all"/>
    @show

    @section('animate_css')
        <link rel="stylesheet" type="text/css" href="{{asset('statics/packs/animate/animate.css')}}" media="all"/>
    @show

    @section('module_css')
        <link rel="stylesheet" type="text/css" href="{{ bra_asset('default' , 'desktop' ,  ROUTE_M , 'css/' . ROUTE_M.'.css')}}?v={{0.01}}" media="all"/>
    @show

    <script type="text/javascript">
        var urlArgs = "v=0.01";
        var csrf_token = "{{ csrf_token() }}";
        //  window.is_prod = "{if !$_W.develop}.min{/if}";
    </script>

</head>
<body class="bra-scroll-bar">

<div class="bra-body bra-wrapper">
    @section('main')
    @show
</div>
@section('requirejs')
    @include("components.requirejs")
@show
@section('footer')
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
