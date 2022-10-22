@extends("themes.default.public.base_layout")

@section('table_form')
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
                    <button type="button" class="bra-btn button is-primary" bra-submit bra-filter="search" data-action="search">查询
                    </button>
                    <button type="reset" class="bra-btn button is-info" bra-submit bra-filter="search"  data-action="reset">重置
                    </button>
                </div>
                <div class="column">
                </div>
                @if($_W['admin_role']['id'] == 1 || $_W['admin_role']['allow_download'])
                    <div class="column is-narrow">
                        <a data-action="export_data" class="bra-btn  is-danger" bra-submit bra-filter="search" bra-action="download">下载数据</a>
                    </div>
                @endif


                @foreach($extra_btns as $extra_btn)
                    {!! $extra_btn !!}
                @endforeach
            </div>
        </form>
    </div>
@endsection

@section('main')

    <div >
        <div class="tableBox" id="mhcms_admin_main_app">
            <div id="bra-table-ctrl" class="has-background-white" style="padding:10px;margin-bottom:10px">
                @foreach( $sub_menus as $_menu)
                    @if($_menu['display'] =="2")
                        @php
                            $_menu = (array)$_menu;
                            $res = build_back_link($_menu, '', $mapping);
                        @endphp
                        <a data-id="{{$_menu['mini']}}_{{$_menu['id']}}" id="{!! $_menu['mini'] !!}_{!!$_menu['id']!!}"

                           class="{!! $_menu['class'] !!}" data-action_type="{!! $_menu['mini'] !!}"
                           data-mini="bottom_action" data-href="{!! $res !!}">{{ trans($_menu['title'])}}
                        </a>
                    @endif

                    @if($_menu['display'] =="1")
                        {!!  build_back_a((array)$_menu ,[], $mapping ?? [] )  !!}
                    @endif

                @endforeach
            </div>
            {{-- handle barls tpl --}}

            @verbatim
                <script type="text/html" id="_upload_bra_tpl">
                    {{#  if(d[d.field][0]){ }}  <img bra-mini="view_image" src="{{d[d.field][0].url}}" style="max-height:100%"/> {{#  } else { }}   {{#  } }}
                </script>
            @endverbatim
        </div>

        <div class="bra-table-idx" id="bra-table-idx">

        </div>

    </div>

@endsection

@section('footer_js')
    <script>
        window.bra_page = {};
        var tpl = `{!!  $template  !!}`;
        require(['tabulator', 'tabulator_editor', 'braui', 'handlebars/handlebars.min', 'layer', 'bra_form', 'Vue'],

            function (Tabulator, TabulatorEditor, braui, Handlebars, layer, bra_form, Vue) {
                $("#table_app").show();
                var extra = [];
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
                extra.unshift({
                    formatter: "rowSelection", titleFormatter: "rowSelection", hozAlign: "center", headerSort: false, cellClick: function (e, cell) {
                        cell.getRow().toggleSelect();
                    }, frozen: true
                });
                var cols = @json($cols);
                if (tpl !== "<div></div>") {
                    cols = extra.concat(cols);
                } else {
                    cols = extra.concat(cols);
                }
                console.log(cols)
                var api_url = "{!! \Bra\core\http\BraRequest::$holder->getUri() !!}";
                bra_page.table = new Tabulator("#bra-table-idx", {
                    maxHeight: 1000,filterMode:"remote",
                    ajaxParams: {bra_action: "post"},
                    ajaxURL: api_url, //ajax URL
                    layout: "fitDataFill", //fit columns to width of table (optional)
                    columns: cols,
                    pagination:true,responsiveLayout:"hide",
                    paginationMode:"remote", //enable remote pagination
                    paginationSize: 15,
                    paginationSizeSelector: true,
                    paginationCounter: "rows",
                    // ajaxURLGenerator:function(url, config, params){
                    //     //url - the url from the ajaxURL property or setData function
                    //     //config - the request config object from the ajaxConfig property
                    //     //params - the params object from the ajaxParams property, this will also include any pagination, filter and sorting properties based on table setup
                    //     braui.params_2_qs(params);
                    //     //return request url
                    //     return url + "&" + braui.params_2_qs(params); //encode parameters as a json object
                    // },
                    dataSendParams:{
                        "page":"__page", //change page request parameter to "pageNo"
                        "size":"__size", //change page request parameter to "pageNo"
                    } ,
                    ajaxResponse:function (url , params , resp){
                        return resp;
                    },
                    ajaxConfig: {
                        headers: {
                            'Accept': "application/json, text/javascript, */*; q=0.01",
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    },
                    rowFormatter: function (row) {
                        if (row.getData().old_data && row.getData().old_data.status) {
                            row.getElement().classList.add("bra-row-" + row.getData().old_data.status); //mark rows with age greater than or equal to 18 as successful;
                        }
                    },
                });

                bra_page.table.on("cellEdited", function(cell){
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
                $("#bra-table-ctrl  a[data-mini='bottom_action']").on('click', function (event ) {

                    var datas = bra_page.table.getSelectedData();
                    if (datas.length === 0) {
                        return layer.msg('最少选择一个');
                    }
                    var ids = [], action = $(this).data('mini');
                    $.each(datas, function (idx, item) {
                        ids.push(item.id);
                    });

                    braui.bra_bat_action(this, {
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    }, function (data) {
                        layer.closeAll();
                        layer.msg(data.msg);
                        bra_page.table.setPage(bra_page.table.getPage());
                    });

                });

                var option_keys = [];
                var params = {};

                bra_form.listen({
                    id: "search",
                    before_submit: function (fields, callback) {
                        console.log(fields , this )

                        switch (this.trigger.data("action")) {
                            case 'search':
                                for (var key in fields) {
                                    if (option_keys.indexOf(key) === -1) {
                                        option_keys.push(key);
                                    }
                                }
                                bra_page.table.options.ajaxParams  = fields
                                return bra_page.table.setData("{!! $_W['current_url'] !!}", fields);
                            case 'reset':

                                bra_page.table.options.ajaxParams  = {bra_action: "post"}
                                return bra_page.table.setData("{!! $_W['current_url'] !!}");
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
                            $current_url = \Bra\core\http\BraRequest::$holder->getUri();
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
