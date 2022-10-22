@extends('install.base_layout')
@section('main')
    <div class="install-box">
        <fieldset class="">
            <legend>运行环境检测</legend>
            <table class="table">
                <thead>
                <tr>
                    <th>环境名称</th>
                    <th>当前配置</th>
                    <th>所需配置</th>
                </tr>
                </thead>
                <tbody>
                @foreach($env as $vo)
                    <tr class="{$vo[4]}">
                        <td>{{$vo[0]}}</td>
                        <td>{{$vo[3]}}</td>
                        <td>{{$vo[2]}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table class="layui-table">
                <thead>
                <tr>
                    <th>目录/文件</th>
                    <th>所需权限</th>
                    <th>当前权限</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dir as $filemod)
                    <tr class="<?php echo $filemod['is_writable'] ? "yes" : 'no'?>">
                        <td><?php echo $filemod['file'] ?></td>
                        <td><span>可写</span></td>
                        <td><?php echo $filemod['is_writable'] ? '可写' : '不可写' ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table class="table">
                <thead>
                <tr>
                    <th>函数/扩展</th>
                    <th>类型</th>
                    <th>结果</th>
                </tr>
                </thead>
                <tbody>
                @foreach($func as $vo)
                    <tr class="{$vo[2]}">
                        <td>{{$vo[0]}}</td>
                        <td>{{$vo[3]}}</td>
                        <td>{{$vo[1]}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </fieldset>

        <div class="step-btns">
            <a href="javascript:history.go(-1);" class="">返回上一步</a>
            @if($check_error)
                <a onclick="alert('please fix all error!')"  class="">进行下一步</a>
            @else
                <a href="/install/index/auth_code" class="">进行下一步</a>

            @endif
        </div>
    </div>

@endsection
