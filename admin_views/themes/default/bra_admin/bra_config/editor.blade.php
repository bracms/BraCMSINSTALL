@extends("themes.default.public.base_layout")

@section('main')
    <div class="tableBox">
        <div class="container has-background-white" style="padding:10px">
            <div class="layui-row">
                <form class="bra-form  has-background-white  padded">

                    {!!  csrf_token_form() !!}
                    <div class="field">
                    <label class="layui-form-label bra-btn is-link">{{$config_name}}</label>
                    </div>
                    <div class="field">
                        <div class="layui-input-block">
							<?php
							list($form_str , $form_script) =  \Bra\bra_admin\forms\EditorUeditorForm::bra_editor('term', $config['term'] ?? '', 'data[term]', '100%', '400px');
							$bra_scripts[] = $form_script;
                            ?>
                            {!! $form_str !!}
                        </div>
                    </div>

                    <div class="field">
                        <div class="layui-input-block">
                            <button class="bra-submit-btn button is-info" bra-submit bra-filter="*">立即提交</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection


@section('footer_js')
    @include("components.bra_admin_post_js")
    @foreach( $bra_scripts as $bra_script)
        {!! $bra_script !!}
    @endforeach
@endsection
