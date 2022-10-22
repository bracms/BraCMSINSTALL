@extends("themes.default.public.base_layout")

@section('main')

    <div class="container" id='bra_form_app'>
        <div class="has-background-white" style="padding:15px">
            <form class="bra-form">
                {!!  csrf_token_form() !!}
                    @foreach ($field_list as $k=>$field)

                    @if($field)
                        <div class="field" id="row_{{$k}}">
                            @if(isset($field['sub_fields']) && $field['sub_fields'])
                                @foreach( $field['sub_fields'] as $sub_field)
                                    <div class="layui-inline">
                                        <label class="layui-form-label">
                                            {{ trans($sub_field['title'])}}
                                            @if($sub_field['is_required'])
                                                <span class="has-text-danger">*</span>
                                            @endif

                                            @if($sub_field['tips_form'] ?? '')
                                                <span class="has-text-danger" data-title="{$sub_field['tips_form']}">?</span>
                                            @endif
                                        </label>

                                        <div class="layui-input-inline"> {{ $sub_field->form_str()}}</div>
                                    </div>
                                @endforeach
                            @else
                            @endif
                                <div class="field has-addons">
                                    <div class="control">
                                        <div class="bra-btn" style="width:100px">
                                            {{ trans($field['title'])}}

                                            @if($field['is_required'] ?? false) <span
                                                    class="has-text-danger">*</span> @endif

                                            @if($field['tips_form'] ?? false)
                                                <span mini="tips" class="has-text-danger" data-title="{{$field['tips_form']}}">?</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="control is-expanded">
                                        {!! $field->form_str() !!}
                                    </div>
                                </div>

                        </div>
                    @endif
                    @endforeach
                <div class="field">
                    <div class="">
                        <button class="button submit-btn is-info" data-action="close"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-close"></i></span>
                            <span>{{ $btn_txt ?? '保存并关闭'}}</span>
                        </button>
                        <button class="button submit-btn is-link" data-action="keep"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-section"></i></span>
                            <span>{{ $btn_txt ?? '保存继续'}}</span>
                        </button>
                        <button class="button submit-btn is-success" data-action="reload"  bra-submit bra-filter="*">
                            <span class="icon"><i class="fas fa-refresh"></i></span>
                            <span>{{ $btn_txt ?? '保存刷新'}}</span>
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
                url: "",
                before_submit: function (fields, cb) {
                    $('.submit-btn').toggleClass('is-loading')
                    cb();
                },
                success: function (data, form) {
                    if (parent.bra_page && parent.bra_page.table) {
                        parent.bra_page.table.setPage(parent.bra_page.table.getPage());
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
                    console.log(data);
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
