@extends('install.base_layout')
@section('top_header')
    @include('install.style')
@endsection

@section('main')
    <div class="install-box">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>数据库配置</legend>
        </fieldset>
        <form id="db-form" class="bra-form layui-form-pane" action="" method="post" bra-id="db-form">
            <div class="field has-addons">
                <div class="control">
                    <div class="button">服务器地址</div>
                </div>
                <div class="control">
                    <input type="text" class="input" name="database[DB_HOST]" value="{{config('database.connections.mysql.host')}}" value="127.0.0.1">
                </div>
                <div class="control"> <div class="button is-static">数据库服务器地址，一般为127.0.0.1</div></div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">数据库端口</div></div>
                <div class="control">
                    <input type="text" class="input" name="database[DB_PORT]" value="{{config('database.connections.mysql.port')}}" value="3306">
                </div>
                <div class="control ">
                    <div class="button is-static">系统数据库端口，一般为3306</div>
                </div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">数据库名称</div></div>
                <div class="control">
                    <input type="text" class="input" name="database[DB_DATABASE]"  value="{{config('database.connections.mysql.database')}}" >
                </div>
                <div class="control "><div class="button is-static">系统数据库名,必须包含字母</div></div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">数据库账号</div></div>
                <div class="control">
                    <input type="text" class="input" name="database[DB_USERNAME]"  value="{{config('database.connections.mysql.username')}}">
                </div>
                <div class="control "><div class="button is-static">连接数据库的用户名</div></div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">数据库密码</div></div>
                <div class="control">
                    <input type="password" class="input"  name="database[DB_PASSWORD]"  value="{{config('database.connections.mysql.password')}}">
                </div>
                <div class="control "><div class="button is-static">连接数据库的密码</div></div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">数据库前缀</div></div>
                <div class="control">
                    <input type="text" class="input" name="prefix" readonly value="bra_">
                </div>
                <div class="control "><div class="button is-static">建议使用默认,数据库前缀必须 '_' 结尾</div></div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">覆盖数据库</div></div>
                <div class="control">

                    <div class="input">
                    <label for="cover" class="radio"> <input type="radio" name="cover" value="1" title="覆盖" checked>覆盖</label>
                    <label for="cover" class="radio"><input type="radio" name="cover" value="0" title="不覆盖" disabled>不覆盖</label>
                    </div>
                </div>
                <div class="control"><div class="button is-static">如果数据库存在将会被覆盖掉</div></div>
            </div>
            <div class="field has-addons">
                <a class="button fl" style="margin-left:120px;" bra-submit="db" bra-filter="db-form">保存配置</a>
            </div>
        </form>
        <form id="install-form" class="bra-form layui-form-pane" bra-id="install-form" method="post"  style="display: none">
            @csrf
            <fieldset class="layui-elem-field layui-field-title">
                <legend>管理账号设置</legend>
            </fieldset>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">管理员账号</div></div>
                <div class="control">
                    <input type="text" class="input" name="user_name" lay-verify="required">
                </div>
                <div class="control">
                <div class="button is-static">管理员账号最少5位</div></div>
            </div>
            <div class="field has-addons">
                <div class="control"><div class="button is-static">管理员密码</div></div>
                <div class="control">
                    <input type="password" class="input" name="password" lay-verify="required">
                </div>
                <div class="control">
                <div class="button is-static">保证密码最少6位</div></div>
            </div>
            <div class="step-btns">
                <a href="javascript:history.go(-1);" class="layui-btn layui-btn-primary layui-btn-big fl">返回上一步</a>
                <a class="layui-btn layui-btn-big layui-btn-normal fr" bra-submit="install" bra-filter="install-form">立即执行安装</a>
            </div>
        </form>
    </div>
    <a class="layui-btn layui-btn-big layui-btn-normal fr" bra-submit="install" bra-filter="install-form">立即执行安装</a>


@endsection

@section('footer_js')
    <script>
        require(['layer', 'jquery', 'bra_form'], function (layer, $, bra_form) {
            bra_form.listen({
                id : 'db-form' ,
                url: "{{ make_url('install/index/save_db') }}",
                before_submit: function (fields, cb) {
                    $('[bra-submit]').toggleClass('is-loading')
                    fields['bra_action'] = 'post';
                    cb(fields);
                },
                success: function (data, form) {
                    layer.msg(data.msg);
                    console.log(data)
                    if(data.code === 1){
                        console.log("show")
                        $('#install-form').show();
                    }
                    if(data.url){
                        setTimeout(function (){
                            window.location.href = data.url
                        } , 2000);
                    }
                },
                error: function (data) {
                    console.log(data);
                    layer.msg(data.msg);
                },
                finish: function (data, form) {
                    $('[bra-submit]').toggleClass('is-loading')
                }
            });

            bra_form.listen({
                id : 'install-form' ,
                url: "{{ make_url('install/index/check_db') }}",
                before_submit: function (fields, cb) {
                    $('[bra-submit]').toggleClass('is-loading')
                    fields['bra_action'] = 'post';
                    cb(fields);
                },
                success: function (data, form) {
                    layer.msg(data.msg);
                    if(data.url){
                        setTimeout(function (){
                            window.location.href = data.url
                        } , 2000);
                    }
                },
                error: function (data) {
                    console.log(data);
                    layer.msg(data.msg);
                },
                finish: function (data, form) {
                    $('[bra-submit]').toggleClass('is-loading')
                }
            });
        });
    </script>
@endsection
