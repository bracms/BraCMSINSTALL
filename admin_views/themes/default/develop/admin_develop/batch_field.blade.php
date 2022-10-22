@extends("themes.default.public.base_layout")


@section('table_form')
@endsection


@section('main')
    <div class="container" id='bra_form_app'>
        <div class="has-background-white" style="padding:15px">
            <form class="bra-form">
                {!!  csrf_token_form() !!}
                <div class="field has-addons">
                    <div class="control">
                        <div class="button" style="width:160px">
                            选择表单类型
                        </div>
                    </div>
                    <div class="control is-expanded">
                        {!! $form_type->form_str() !!}
                    </div>
                </div>

                <div class="field">
                    <div class="">
                        <button class="button submit-btn is-info" data-action="close"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-close"></i></span>
                            <span>{{ $btn_txt ?? '保存并关闭'}}</span>
                        </button>

                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('footer_js')

    <script>
        require(['layer', 'jquery', 'bra_form'], function (layer, $, bra_form) {
            console.log(bra_form)
            bra_form.listen({
                url: "{!! $_W['current_url']  !!} ",
                before_submit: function (fields, cb) {
                    $('.submit-btn').toggleClass('is-loading')
                    cb();
                },
                success: function (data, form) {
                    if (parent.bra_page && parent.bra_page.table) {
                        parent.bra_page.table.setPage(parent.bra_page.table.getPage());
                    }


                    return  layer.msg(data.msg);
                },

                error: function (data) {
                    layer.msg(data.msg);
                },
                finish: function (data, form) {
                    $('.submit-btn').toggleClass('is-loading')
                }
            });
        });
    </script>

    {!! $form_type->form_script !!}
@endsection
