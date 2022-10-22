@extends('install.base_layout')


@section('main')
    <div class="install-box">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>
                填写授权码 <a class="has-text-link" href="https://www.bracms.com/">官方网址</a>

            </legend>
            <form class="bra-form" method="post" bra-id="*">

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">授权码</label>
                    <div class="layui-input-block">
                <textarea name="product_licence_code" id="product_licence_code" placeholder="请输入授权码"
                          class="layui-textarea">{{config("licence.product_licence_code")}}</textarea>
                    </div>
                </div>
            </form>

        </fieldset>


        <div class="step-btns">
            <a href="javascript:history.go(-1);" class="layui-btn layui-btn-primary layui-btn-big fl">返回上一步</a>
            <a bra-submit bra-filter="*" class="layui-btn layui-btn-big layui-btn-normal fr">进行下一步</a>
        </div>
    </div>
@endsection

@section('footer_js')
    @include("components.bra_admin_post_js")
@endsection
