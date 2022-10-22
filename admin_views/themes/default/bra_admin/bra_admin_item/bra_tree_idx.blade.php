@extends("themes.default.bra_admin.bra_admin_item.bra_idx")


@section('table_form')
    <style>
        .tabulator-row{
            border-top:1px solid #aaa;;
        }
        .tabulator-row .tabulator-frozen, .tabulator .tabulator-header .tabulator-frozen.tabulator-frozen-left{
            border-right:1px solid #aaa !important;
        }
        .tabulator-row .tabulator-frozen{
            border-bottom:1px solid #aaa;
        }
        .tabulator-row.bra-row-99{background:#f9fefa}
        .tabulator-row.bra-row-1{background:#ffffef}
        .tabulator-row.bra-row-2{background:#ffffef}
        .tabulator-row.bra-row-3{background:#efffff}
        .tabulator-row.bra-row-4{background:#f6efff}
    </style>
    <div class="has-background-white" id="table_app" style="padding:15px;">
        <form class="bra-form" method="post" bra-id="search">
            <div class="columns is-multiline is-mobile">
                @foreach( $form_fields as $k=>$form_field)
                    @if ($form_field->field['is_admin_filter'] == 1 && $form_field->field['form_type'])
                        <div class="bra-form-item column  is-6-tablet  is-3-desktop is-one-fifth-widescreen is-2-fullhd" id="row_{!! $k !!}">
                            <div class="field has-addons">
                                <div class="control">
                                    <label style="width:85px" class="is-static button">{!! $form_field->field['title'] !!}</label>
                                </div>
                                <div class="control is-expanded">
                                    {!! $form_field->form_str_filter() !!}
                                </div>
                            </div>
                        </div>
                    @endif

                @endforeach

                @if($sort_keys)
                    <div class="bra-form-item column  is-6-tablet  is-3-desktop is-3-widescreen" id="row_{$k}">
                        <div class="field has-addons ">
                            <label class="control">
                                <span class="is-static button" style="width:85px">排序</span>
                            </label>
                            <div class="control is-expanded">
                                <div class="select">
                                    <select name="order" class="bra-select" style="width:100%">
                                        <option value="">
                                            自动排序
                                        </option>
                                        @foreach($sort_keys as $field_name)
                                            <option value="{$field_name} desc">
                                                {!! $fields[$field_name]['title'] !!} 倒叙
                                            </option>

                                            <option value="{$field_name} asc">
                                                {!! $fields[$field_name]['title'] !!} 顺序
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="columns is-multiline is-mobile">
                <div class="column is-narrow">
                    <input type="hidden" name="bra_action" value="post">
                    <button type="button" class="bra-btn button is-primary"
                            bra-submit bra-filter="search" data-action="search" >查询
                    </button>
                    <button type="reset" class="bra-btn button is-info"
                            bra-submit bra-filter="search" data-action="reset"
                    >重置
                    </button>
                </div>
                <div class="column">
                </div>
                @if($_W['admin_role']['id'] == 1 || $_W['admin_role']['allow_download'])
                    <div class="column is-narrow">
                        <a data-action="export_data" class="bra-btn  is-danger" bra-submit bra-filter="download">下载数据</a>
                    </div>
                @endif


                @foreach($extra_btns as $extra_btn)
                    {!! $extra_btn !!}
                @endforeach
            </div>
        </form>
    </div>
@endsection


@section('footer_js')

    <script>
        window.bra_page = {};
        var tpl = `{!!  $template  !!}`;
        require(['tabulator', 'tabulator_editor', 'braui', 'handlebars/handlebars.min', 'layer', 'bra_form'],
            function (Tabulator, TabulatorEditor, braui, Handlebars, layer, bra_form) {
                $("#table_app").show();

                var extra = [];

                var hideIcon = function (cell, formatterParams, onRendered) { //plain text value
                    return "<i class='brafont bra-eye-open'></i>";
                };
                extra.unshift({
                    title: '操作',
                    headerSort: false, frozen: true,
                    formatter: function (cell, formatterParams, onRendered) {
                        var cell_row = cell.getRow();
                        var template = Handlebars.compile(tpl);
                        //console.log(tpl ,cell_row._row.data)
                        return template({d: cell_row._row.data});
                    }
                });
                var cols = @json($cols);
                if (tpl !== "<div></div>") {
                    cols = cols.concat(extra);
                } else {
                    cols = cols.concat(extra);
                }


                var api_url = "{!! \Bra\core\http\BraRequest::$holder->getUri() !!}";
                bra_page.table = new Tabulator("#bra-table-idx", {
                    maxHeight: 1000,
                    ajaxParams: {bra_action: "post"},
                    ajaxURL: api_url, //ajax URL
                    layout: "fitData", //fit columns to width of table (optional)
                    columns: cols,
                    dataTree: true,
                    dataTreeStartExpanded:true, //start with an expanded tree

                    dataTreeChildField: "children",
                    ajaxResponse: function (url, params, response) {
                        return response.data;
                    },
                    ajaxConfig: {
                        headers: {
                            'Accept': "application/json, text/javascript, */*; q=0.01",
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    }
                });
                bra_page.table.on("cellEdited", function (cell) {
                    var cell_row = cell.getRow();
                    var tpl = `{!! $editor_url !!}`;
                    var template = Handlebars.compile(tpl);
                    var $editor_url = template({d: cell_row._row.data});
                    console.log($editor_url);
                    var params = {};
                    params[cell._cell.column.field] = cell._cell.value;
                    $.post($editor_url, {
                        data: params,
                        _token: "{{ csrf_token() }}"
                    }, function (data) {
                        if (data.code != 1) {
                            layer.msg(data.msg);
                        }
                        bra_page.table.setPage(bra_page.table.getPage());
                    }, 'json');
                })

                /*头工具栏事件*/
                $("#bra-table-ctrl  a[data-mini='bottom_action']").on('click', function (obj) {
                    console.log(obj, action = $(this).data('id'));
                    var el = "#" + $(this).data('id');
                    var datas = bra_page.table.getData('selected');
                    if (datas.length == 0) {
                        return layer.msg('最少选择一个');
                    }
                    console.log(bra_page.table.getData('selected'));
                    var ids = [], action = $(this).data('mini');
                    $.each(datas, function (idx, item) {
                        ids.push(item.id);
                    });

                    if (action === 'bottom_action') {
                        braui.bra_bat_action(el, {
                            ids: ids,
                            _token: "{{ csrf_token() }}"
                        }, function (data) {
                            layer.closeAll();
                            layer.msg(data.msg);
                            bra_page.table.setPage(bra_page.table.getPage());
                        });
                    }

                });

                var option_keys = [];

                bra_form.listen({
                    id: "search",
                    url: api_url,
                    before_submit: function (fields, cb) {

                        switch (this.trigger.data("action")) {
                            case 'search':
                                for (var key in fields) {
                                    if (option_keys.indexOf(key) === -1) {
                                        option_keys.push(key);
                                    }
                                }
                                return bra_page.table.setData(api_url, fields);
                            case 'reset':

                                return bra_page.table.setData(api_url, {bra_action: "post"});
                            case 'download':
                                var _btn_action = bra_form.trigger.data('action');
                                @yield('extra_acts')

                                    for (var key in fields) {
                                    if (option_keys.indexOf(key) === -1) {
                                        option_keys.push(key);
                                    }
                                }
                                /*检查 已经检索过的索引是否 被取消了*/
                                for (var key in option_keys) {
                                    var val = option_keys[key];
                                    if (!fields[val]) {
                                        delete fields[val];
                                    }
                                }
                                fields._bra_download = 1;
                                fields._btn_action = _btn_action;
							<?php
							use Bra\core\http\BraRequest;$current_url = BraRequest::$holder->getUri();
							if (strpos($current_url, '?') === false) {
								$current_url = $current_url . "?";
							} else {
								$current_url = $current_url . "&";
							}
							?>
                                var api_url = "{!!  $current_url !!}";

                                var url = api_url + braui.params_2_qs(fields);

                                window.location = url;
                                break;

                            default:
                                console.log(this.filter)
                        }
                    }
                });

                bra_page.reloadPage = function (){
                    bra_page.table.setPage(bra_page.table.getPage());
                }

            });
    </script>

    @foreach( $form_fields as $form_field)
        @if ($form_field)
            {!! $form_field->form_script_filter() !!}
        @endif
    @endforeach

    @yield('extra_styles')
@endsection
