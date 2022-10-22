@extends("themes.default.public.base_layout")


@section('table_form')

    <div class="container">
        <div class="section">
            <form bra-id="*" action="" class="bra-form form">
                {!!  csrf_token_form() !!}
                <div class="field has-addons">
                    <div class="control">
                        <div class="button">表名</div>
                    </div>
                    <div class="control">
             <div class="input">{{$table_name}}</div>
                    </div>
                </div>
                <div class="field has-addons">
                    <div class="control">
                        <div class="button">选择模块</div>
                    </div>
                    <div class="control">
                <?php
                $opts = D('model')->load_options('module_id');
                echo \Bra\bra_admin\forms\RadioForm::radio($opts, 'module', '', '');
                ?>     </div>
                </div>


                <div class="field ">
                    <input type="hidden" name="bra_action" value="post">

                    <a class="button is-info" bra-submit bra-filter="*">保存</a>
                </div>


            </form>
        </div>
    </div>

@endsection


@section('main')
@endsection

@section('footer_js')
    @include("components.bra_admin_post_js")
@endsection