@extends('themes.default.public.base_layout')
@section('main')
<div class="section container">
    <form class="bra-form">

        {!!  csrf_token_form() !!}
        <div class="remote-qiniu layui-row" >

            <div class="field has-addons">

                <div class="control">
                    <div class="bra-btn button is-info">自定义访问域名</div>
                </div>

                <div class="control is-expanded">
                    <input type="text" name="{{$provider['sign']}}[url]" class="input is-primary" value="{{$config['url'] ?? ''}}" placeholder="" />
                </div>
                <div class="control">
                    <div class="bra-btn button is-grey">
                        例如:  http://www.bracms.com/
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="">
                    <button name="submit" bra-submit bra-filter="*"  class="bra-btn button layui-btn-primary" value="submit">保存配置</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@section('footer_js')

    @include("components.bra_admin_post_js")
@endsection
