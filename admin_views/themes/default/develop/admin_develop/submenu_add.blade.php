@extends("themes.default.public.base_layout")


@section('table_form')
@endsection


@section('main')

    <div class="listBox clfx">
        <form class="bra-form form-horizontal" target="zbn_cms" method="post">
            {!!  csrf_token_form() !!}
            <div class="tableBox">
                <table class="table is-bordered" bordercolor="#e1e6eb" cellspacing="0" width="100%">
                    <tr style=" border-bottom: 1px solid #dbdbdb;">
                        <td style="text-align: left">菜单名称</td>
                        <td style="text-align: left">MCA</td>
                        <td style="text-align: left">参数</td>
                        <td style="text-align: left">排序</td>
                        <td style="text-align: left">位置</td>
                        <td style="text-align: left">按钮CSS</td>
                        <td style="text-align: left">行为</td>
                        <td style="text-align: left">图标</td>
                        <td style="text-align: center">操作</td>
                    </tr>
                    <tbody id="jq_action_list">
                    @foreach( $menuList as $var)

                        <tr>
                            <td><input type="text" name="data[{{$var['id']}}][title]" value="{{$var['title']}}"
                                       class="form-control w100"/></td>
                            <td>
                                <input type="text" name="data[{{$var['id']}}][module]" value="{{$var['module']}}"
                                       class="form-control w100"/>
                                <input type="text" name="data[{{$var['id']}}][app]" value="{{$var['app']}}"
                                       class="form-control w100"/>


                                <input type="text" name="data[{{$var['id']}}][ctrl]" value="{{$var['ctrl']}}" class="form-control w100"/>
                                <input type="text" name="data[{{$var['id']}}][act]" value="{{$var['act']}}" class="form-control w100"/>
                            </td>
                            <td>
                                <input type="text" name="data[{{$var['id']}}][params]" value="{{$var['params']}}"
                                       class="form-control w100"/>
                            </td>
                            <td>
                                <input type="text" name="data[{{$var['id']}}][listorder]" value="{{$var['listorder']}}"
                                       class="form-control "/>
                            </td>
                            <td>
                                <select class="form-control" lay-ignore="" name="data[{{$var['id']}}][display]" class="manageSelect w100">
                                    <option value="0" @if( $var['display']==0) selected @endif >右侧子菜单</option>
                                    <option value="1"  @if( $var['display']==1)selected @endif >上方子菜单/主导航</option>
                                    <option value="2" @if( $var['display']==2)selected @endif >下方批量处理</option>
                                    <option value="3"  @if( $var['display']==3)selected @endif >左侧子操作</option>
                                </select>
                                {{$var['id']}}
                            </td>
                            <td>
                                <input type="text" name="data[{{$var['id']}}][class]" value="{{$var['class']}}" class="form-control w100"/>
                            </td>
                            <td>
                                <input type="text" name="data[{{$var['id']}}][mini]" value="{{$var['mini']}}" class="form-control w100"/>
                            </td>
                            <td>
                                <input type="text" name="data[{{$var['id']}}][icon]" value="{{$var['icon']}}" class="form-control w100"/>
                            </td>
                            <td>
                                <input type="hidden" name="data[{{$var['id']}}][id]" value="{{$var['id']}}"/>
                                <input type="hidden" name="data[{{$var['id']}}][parent_id]" value="{{$var['parent_id']}}"/>

                                @foreach( $sub_menus as $_menu)
                                    {!!  build_back_a((array)$_menu ,[], $mapping)  !!}
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <td colspan="7">
                            <a class="button submit-btn is-primary" bra-submit bra-filter="*">保存</a>
                            <input type="hidden" name="bra_action" value="post">
                            <a href="javascript:void(0);" id="jq_action_add" class="button submit-btn is-success">新增一行</a>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
@endsection

@section('footer_js')

    <script>
        var parent_app = "{{$parent['app']}}";
        var parent_module = "{{$parent['module']}}";
        var parent_ctrl  = "{{$parent['ctrl']}}";
        require(['bra_form' , "jquery" ,'layer'], function (bra_form ,$, layer) {
            bra_form.listen({
                url : "{!! $_W['current_url'] !!}" ,
                success(ret){
                    console.log(ret)
                    layer.msg(ret.data.msg);
                    parent.layer.closeAll();
                },

                error: function (data) {
                    layer.msg(data.msg);
                },
                finish: function (data, form) {
                    $('.submit-btn').toggleClass('is-loading')
                }

            });


            $(document).ready(function (e) {
                var action_num = 0;
                $("#jq_action_add").click(function () {
                    action_num++;
                    var html = '<tr id="menu_action_' + action_num + '"> ';
                    html += '<td>';
                    html += '<input type="text" name="new[' + action_num + '][title]" value=""  class="form-control w100"/>';
                    html += '</td> ';

                    html += '  <td>';
                    html += '<input type="text" name="new[' + action_num + '][app]" value="'+ parent_app +'" class="form-control w100" />';
                    html += '<input type="text" name="new[' + action_num + '][module]" value="'+ parent_module +'" class="form-control w100" />';
                    html += '<input type="text" name="new[' + action_num + '][ctrl]" value="'+ parent_ctrl +'" class="form-control w100" />';
                    html += '<input type="text" name="new[' + action_num + '][act]" value="" class="form-control w100" />';
                    html += '</td>';

                    html += '<td>';
                    html += '<input type="text" name="new[' + action_num + '][params]" value=""  class="form-control w100"/>';
                    html += '</td> ';


                    html += ' <td>';
                    html += '<input type="text" name="new[' + action_num + '][listorder]"    value="100" class="form-control w50" />';
                    html += '</td>';
                    html += '<td> <select lay-ignore name="new[' + action_num + '][display]" class="form-control w100"> <option value="0">右侧子操作</option>';
                    html += ' <option value="1">主导航/上方子操作</option>';
                    html += ' <option value="2">下方批处理</option>';
                    html += '<option value="3">表头子操作</option></select></td>';

                    html += '<td>';
                    html += '<input type="text" name="new[' + action_num + '][class]" value=""  class="form-control w100"/>';
                    html += '</td> ';

                    html += '<td>';

                    html += '<input type="text" name="new[' + action_num + '][mini]" value=""  class="form-control w100"/>';
                    html += '</td> ';
                    html += '<td>';
                    html += '<input type="text" name="new[' + action_num + '][icon]" value=""  class="form-control w100"/>';
                    html += '</td> ';
                    html += '<td><a href="javascript:void(0);" onclick="$(\'#menu_action_' + action_num + '\').remove();" class="btn btn-warning" >移除</a></td> </tr>';
                    $("#jq_action_list").append(html);
                });
            });
        });
    </script>
@endsection
