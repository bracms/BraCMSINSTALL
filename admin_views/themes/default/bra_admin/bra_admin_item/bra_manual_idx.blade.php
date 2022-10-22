@extends("themes.default.public.base_layout")

@section('table_form')

	<?php
	$editor_url = "";
	?>
    <div class="has-background-white" id="table_app" style="padding:15px;">
        <form class="bra-form" method="post" bra-id="search">
            <div class="columns is-multiline is-mobile">
                @foreach( $filter_fields as $k=>$field)
                    <div class="bra-form-item column  is-6-tablet  is-3-desktop is-one-fifth-widescreen is-2-fullhd" id="row_{!! $k !!}">
                        <div class="field has-addons">
                            <div class="control">
                                <label style="width:85px" class="is-static button">{!! $field['title'] !!}</label>
                            </div>
                            <div class="control is-expanded">
                                {!! $field->form_str_filter() !!}
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
            <div class="columns is-multiline is-mobile">
                <div class="column is-narrow">
                    @if($filter_fields)
                        <input type="hidden" name="bra_action" value="post">
                        <button type="button" class="bra-btn button is-primary"
                                bra-submit bra-filter="search" data-action="search">查询
                        </button>
                        <button type="reset" class="bra-btn button is-info"
                                bra-submit bra-filter="search" data-action="reset"
                        >重置
                        </button>
                    @endif
                </div>
                <div class="column">
                </div>

                @foreach($extra_btns as $extra_btn)
                    {!! $extra_btn !!}
                @endforeach
            </div>
        </form>
    </div>
@endsection

@section('main')

    <div style="min-width:980px">
        <div class="tableBox" id="bracms_idx_app">
            <div id="bra-table-ctrl" class="is-padding p-a is-margin m-b has-bg-white">
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
                        {!!  build_back_a((array)$_menu ,[], $mapping)  !!}
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
        require(['tabulator',  'braui', 'handlebars/handlebars.min', 'layer', 'bra_form'],

            function (Tabulator,braui, Handlebars, layer, bra_form) {
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
                    // cols = cols[0];
                    cols = extra.concat(cols);
                }

                console.log(cols)
                var api_url = "{!! \Bra\core\http\BraRequest::$holder->getUri() !!}";

                bra_page.table = new Tabulator("#bra-table-idx", {
                    // maxHeight: $("#bra_app").height() - $(".sub-menu-top").height() - $(".bra-body-header").height() - $("#bracms_idx_app").height() - 40,
                    maxHeight: 1000,
                    ajaxParams: {bra_action: "post"},
                    ajaxURL: api_url, //ajax URL
                    layout: "fitDataFill", //fit columns to width of table (optional)
                    columns: cols,
                    pagination: "remote", //enable remote pagination
                    paginationSize: "{{ $_W['site']['config']['page_size'] ?? 10}}",
                    paginationSizeSelector: [5, 10, 20, 25, 30, 50],
                    footerElement: "<button id='_total_rows'>Custom Button</button>",
                    ajaxResponse: function (url, params, response) {

                        bra_page.table.setHeight($("#bra_app").height() - $(".sub-menu-top").height() - $(".bra-body-header").height() - $("#bracms_idx_app").height() - 40); //set table height to 500px

                        $("#_total_rows").html('总:' + response.total)
                        console.log(response.total)
                        return response.data;
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
                    paginationDataSent: {
                        "size": "__page_size",
                    },
                    paginationDataReceived: {},
                    dataChanged: function (data) {
                        console.log(data)
                        //data - the updated table data
                    },
                    cellEdited: function (cell) {
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
                    },
                });


                /*头工具栏事件*/
                $("#bra-table-ctrl  a[data-mini='bottom_action']").on('click', function (event ) {
                    var datas = bra_page.table.getData('selected');
                    if (datas.length == 0) {
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

                bra_form.listen({
                    id: "search",
                    url: api_url,
                    before_submit: function (fields, cb) {

                        switch (this.trigger.data('action')) {
                            case 'search':
                                for (var key in fields) {
                                    if (option_keys.indexOf(key) === -1) {
                                        option_keys.push(key);
                                    }
                                }
                                console.log(fields)
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


            });
    </script>

    @foreach( $filter_fields as $filter_field)
        {!! $filter_field->form_script_filter() !!}
    @endforeach


    @yield('extra_styles')
@endsection
