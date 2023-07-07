@yield('bra_top')<!DOCTYPE html>
<html style="height: auto">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
    <link type="favicon" rel="shortcut icon" href="/favicon.ico"/>
    <meta name="Generator" content="BraCMS"/>
    <title>{{$seo['seo_title']}}</title>
    @section('css_icon')
        <link rel="stylesheet" href="{{asset('statics/css/bra_icon.css')}}">
    @show
    @section('bra_css')
        <link rel="stylesheet" type="text/css" href="{{asset('/statics/packs/bulma/bulma.min.css')}}" media="all"/>
        <link rel="stylesheet" type="text/css" href="{{asset('/statics/packs/braui/bra-admin.css')}}" media="all"/>
    @show


    @section('animate_css')
        <link rel="stylesheet" type="text/css" href="{{asset('statics/packs/animate/animate.css')}}" media="all"/>
    @show

    @section('module_css')
        <link rel="stylesheet" type="text/css" href="{{ bra_asset( 'default' , 'mobile' , ROUTE_M , 'css/' . ROUTE_M.'.css')}}" media="all"/>
    @show

    <script type="text/javascript">
        var urlArgs = "v={{$_W['site']['config']['bra_suffix'] ?? '1'}}";
        var single_url = "{{make_url('bra_admin/admin_api/update_field')}}";
        var upload_url = "{{make_url('bra/annex/upload')}}";
        var save_attach = "{{make_url('bra/annex/save')}}";
        var csrf_token = "{{ csrf_token() }}";
        //  window.is_prod = "{if !$_W.develop}.min{/if}";
    </script>

    @section('wechat_jsskd')
        @if(is_weixin())
            <script>
                var js_api_list = [
                    /*所有要调用的 API 都要加到这个列表中*/
                    'checkJsApi',
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareWeibo',
                    'hideMenuItems',
                    'showMenuItems',
                    'hideAllNonBaseMenuItem',
                    'showAllNonBaseMenuItem',
                    'translateVoice',
                    'startRecord',
                    'stopRecord',
                    'onRecordEnd',
                    'playVoice',
                    'pauseVoice',
                    'stopVoice',
                    'uploadVoice',
                    'downloadVoice',
                    'chooseImage',
                    'previewImage',
                    'uploadImage',
                    'downloadImage',
                    'getNetworkType',
                    'openLocation',
                    'getLocation',
                    'hideOptionMenu',
                    'showOptionMenu',
                    'closeWindow',
                    'scanQRCode',
                    'chooseWXPay',
                    'openProductSpecificView',
                    'addCard',
                    'chooseCard',
                    'openCard',
                    'openAddress'
                ];
            </script>
        @else
            <script>
                var js_api_list = [];
            </script>
        @endif
    @show
</head>
<body class="bra-scroll-bar">

<div class="bra-body bra-wrapper">
    @section('main')
    @show
</div>
@section('requirejs')
    <script type="text/javascript" src="{{asset('statics/js/require.js')}}"></script>
    <script type="text/javascript" src="{{asset('statics/js/config.js')}}"></script>
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
