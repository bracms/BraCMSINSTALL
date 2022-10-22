@extends("themes.default.public.base_layout")


@section('main')
    <div class="container  is-mobile">
        <div class=" section has-background-white">
            <form class="bra-form" method="post">

                {!!  csrf_token_form() !!}
                <div class="bra-dialog is-primary">
                    <ul class="bra-dialog-header">
                        <li data-tab="base">小程序设置</li>
                    </ul>
                    <div class="blockquote" id="base">

                        <div class="field is-grouped field-body">

                            <label class="field has-addons" mini="tips" data-title="开启以后,关注和网页授权会自动生成账号!">
                                <div class="control">
                                    <div class="button button">生成账号</div>
                                </div>
                                <div class="control">
                                    <div class="input">
                                        <input type="checkbox" name="auto_account" value="1" @if( isset($config['auto_account']) && $config['auto_account']==1) checked @endif
                                        >
                                    </div>
                                </div>
                            </label>

                            <label class="field has-addons">
                                <div class="control">
                                    <div class="button">强制授权</div>
                                </div>
                                <div class="control">
                                    <div class="input">
                                        <input type="checkbox" name="force_wechat" value="1"
                                               @if( isset($config['force_wechat']) && $config['force_wechat']==1) checked @endif>
                                    </div>
                                </div>
                            </label>

                            <label class="field has-addons" mini="tips" data-title="未绑定开放平台禁止开启!">
                                <div class="control">
                                    <div class="button">使用开放平台</div>
                                </div>
                                <div class="control">
                                    <div class="input">
                                        <input type="checkbox" name="allow_union" value="1"
                                               @if( isset($config['allow_union']) && $config['allow_union']==1)  checked @endif>
                                    </div>
                                </div>
                            </label>

                            <label class="field has-addons">
                                <div class="control">
                                    <div class="button">强制验证手机号码</div>
                                </div>
                                <div class="control">
                                    <div class="input">
                                        <input type="checkbox" name="force_mobile" value="1"
                                               @if( isset($config['force_mobile']) && $config['force_mobile']==1) checked  @endif>
                                    </div>
                                </div>
                            </label>

                        </div>


                        <div class="field has-addons">
                            <div class="control">
                                <a class="button is-primary" bra-submit bra-filter="*">立即提交</a>
                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection
@section('footer_js')
    @include("components.bra_admin_post_js")
@endsection
