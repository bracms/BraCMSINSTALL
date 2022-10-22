@extends('themes.default.public.base_layout')

@section('main')
    <div class="container" id='bra_form_app'>
        <div class="has-bg-white" style="padding:15px">
            <form class="bra-form">
                {!!  csrf_token_form() !!}
                @foreach ($field_list as $k=>$field)
                    @if($field)
                        <div class="field has-addons" {{$k}}>
                            <div class="control">
                                <div class="button" style="width:160px">
                                    {{ trans($field['title'] ?? '')}}

                                    @if($field['is_required']) <span  class="has-text-danger">*</span> @endif

                                    @if($field['tips_form'])
                                        <span mini="tips" class="has-text-danger" data-title="{{$field['tips_form']}}">?</span>
                                    @endif
                                </div>
                            </div>
                            <div class="control is-expanded">
                                {!! $field->form_str() !!}
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="field">
                    <div class="">
                        <div class="button submit-btn is-info" data-action="close"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-close"></i></span>
                            <span>{{ $btn_txt ?? '保存并关闭'}}</span>
                        </div>
                        <div class="button submit-btn is-link" data-action="keep"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-section"></i></span>
                            <span>{{ $btn_txt ?? '保存继续'}}</span>
                        </div>
                        <div class="button submit-btn is-success" data-action="reload"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-refresh"></i></span>
                            <span>{{ $btn_txt ?? '保存刷新'}}</span>
                        </div>

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
                url: "",
                before_submit: function (fields, cb) {
                    $('.submit-btn').toggleClass('is-loading')
                    cb();
                },
                success: function (data, form) {
                    if (parent.bra_page && parent.bra_page.table) {
                        parent.bra_page.reloadPage();
                    }


                    if (form.trigger.data('action') === 'close' && parent.layer) {
                        return parent.layer.closeAll();
                    }

                    if (form.trigger.data('action') === 'reload') {
                        return window.location.reload();
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

    @foreach( $field_list as $field)
        {!! $field->form_template !!}
    @endforeach

    @foreach( $field_list as $field)
        {!! $field->form_script !!}
    @endforeach


@endsection
