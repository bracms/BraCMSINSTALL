@extends("themes.default.public.base_layout")

@section('main')
    <div class="container has-background-white" id="bra_app" v-cloak="" style="padding:10px;">
        <form class="bra-form" bra-id="*">
            {!!  csrf_token_form() !!}
            <div class=" tab  tab-card">
                <div class="tabs">
                    <ul>
                        <li @click="current_idx = 0" :class="{'is-active' : current_idx == 0}"><a>网站设置</a></li>
                        <li @click="current_idx = 1" :class="{'is-active' : current_idx == 1}"><a>安全设置</a></li>
                        <li @click="current_idx = 2" :class="{'is-active' : current_idx == 2}"><a>地图配置</a></li>
                        <li @click="current_idx = 3" :class="{'is-active' : current_idx == 3}"><a>功能设置</a></li>
                        <li @click="current_idx = 4" :class="{'is-active' : current_idx == 4}"><a>网站信息</a></li>
                    </ul>
                </div>

                <div class=" tab-content has-bg-white">
                    <!--web-->
                    <div class=" tab-item  show" id="idx_1" v-show="current_idx == 0">
                        <div class="field has-addons is-grouped field-body">

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">是否关闭</div>
                                </div>
                                <div class="control">
                                    <label class="input">
                                        <input type="checkbox" name="close_site" value="1" @if(isset($config['close_site']) &&
                                   $config['close_site']==1) checked @endif>
                                    </label>
                                </div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">关闭原因</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="close_site_tips" placeholder="关闭网站用户提示语" class="input"
                                           value="{{$config['close_site_tips'] ?? '网站维护中'}}"/>
                                </div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">站点名字</div>
                                </div>
                                <div class="input-block">
                                    <input type="text" name="site_name" required value="{{$config['site_name']??''}}"
                                           placeholder="SEO地名" autocomplete="off" class="input">
                                </div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">浏览基数</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="hit[hit_base]" value="{{$config['hit']['hit_base']??'50'}}"
                                           placeholder="点击基数" autocomplete="off" class="input">
                                </div>
                                <div class="control">
                                    <div class="button is-static is-static">范围为1 - 此数值点击量</div>
                                </div>
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">统计代码</div>
                            </div>
                            <div class="control is-expanded">
                            <textarea name="tongji" placeholder="统计代码"
                                      class="bra-textarea textarea">{{$config['tongji'] ??""}}</textarea>
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('Logo上传')}}</div>
                            </div>
                            <div class="control is-expanded">
								<?php
								use Bra\bra_admin\forms\BraField;
								$field_config = [
									'data_source_type' => '',
									'data_source_config' => "jpg,gif,png,jpeg",
									'form_name' => 'logo',
									'title' => 'Logo上传',
									'field_name' => 'logo',
									'is_admin_form' => 1,
									'length' => 1,
									'form_type' => 'upload_image',
									'form_id' => 'logo',
									'class_name' => 'select form-control',
									'form_group' => ''
								];
								$field = D("model_field")->get_item($field_config , false);
								$field_list[] = $field = BraField::createField($field , ['logo' => $config['logo']]);
								?>
                                {!! $field->form_str() !!}
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('分销海报')}}</div>
                            </div>
                            <div class="control is-expanded">
								<?php
								$field_config = [
									'data_source_type' => '',
									'data_source_config' => "jpg,gif,png,jpeg",
									'form_name' => 'poster',
									'slug' => '海报上传',
									'field_name' => 'poster',
									'is_admin_form' => 1,
									'is_multiple' => 1,
									'form_type' => 'upload_image',
									'max_count' => 9,
									'default_value' => $config['poster'] ?: "",
									'form_id' => 'poster',
									'form_group' => ''
								];
								$field = D("model_field")->get_item($field_config);
								$field_list[] = $field = BraField::createField($field , ['poster' => $config['poster']]);

								?>

                                    {!! $field->form_str() !!}
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('默认主题')}}</div>
                            </div>

                            <div class="control is-expanded">
								<?php
								$field_config = [
									'data_source_type' => 'bra_theme',
									'data_source_config' => "bra_theme",
									'form_name' => 'theme',
									'field_name' => 'theme',
									'form_type' => 'radio',
									'default_value' =>  $config['theme'] ?: 'default',
									'form_id' => 'field_type_name',
									'class_name' => ' form-control',
									'pk_key' => 'theme_dir',
									'name_key' => 'theme_name',
									'form_property' => '  required ',
									'primary_option' => 'Please,Select',
									'form_group' => ''
								];
								$field = D("model_field")->get_item($field_config);
								$fields[] = $field = BraField::createField($field , []);
								?>
                                {!! $field->form_str() !!}
                            </div>

                        </div>

                    </div>

                    <!--security-->
                    <div class=" tab-item" data-tab="security" v-show="current_idx == 1">

                        <div class="field is-grouped field-body">
                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">{{lang('限制后台访问域名')}}</div>
                                </div>
                                <div class="control">
                                    <label class="input">
                                        <input type="checkbox" name="limit_admin_domain" value="1" @if(isset($config['limit_admin_domain']) && $config['limit_admin_domain'] ==1) checked @endif>
                                    </label>
                                </div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">管理员编号</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="admin_id" value="{{$config['admin_id'] ??''}}"
                                           placeholder="管理员admin_id" autocomplete="off" class="input">
                                </div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">缓存后缀</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="bra_suffix" value="{{$config['bra_suffix'] ??'0'}}"
                                           placeholder="bra_suffix" autocomplete="off" class="input">
                                </div>
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('敏感词过滤 逗号（,）隔开')}}</div>
                            </div>
                            <div class="control is-expanded">
                                <textarea name="bad_words" placeholder="敏感词过滤" class="bra-textarea textarea">{{$config['bad_words']??""}}</textarea>
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('后台访问IP 白名单逗号（,）隔开')}}</div>
                            </div>
                            <div class="control is-expanded">
                                <textarea name="white_ip" placeholder="后台访问IP ," class="bra-textarea textarea">{{$config['white_ip'] ??""}}</textarea>
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('IP 黑名单逗号（,）隔开')}}</div>
                            </div>
                            <div class="control is-expanded">
                                <textarea name="bad_ip" placeholder="IP 黑名单中前后台都不能访问" class="bra-textarea textarea">{{$config['bad_ip']??''}}</textarea>
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('敏感词过滤替代字符')}}</div>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="bad_word_replace" value="{{$config['bad_word_replace']??'*'}}"
                                       placeholder="敏感词过滤替代字符" autocomplete="off" class="input">
                            </div>
                        </div>

                    </div>
                    <!--Map Config-->

                    <div class=" tab-item" data-tab="map" v-show="current_idx == 2">

                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('腾讯地图密钥')}}</div>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="map[tx_key]" value="{{$config['map']['tx_key']??''}}"
                                       placeholder="腾讯地图密钥"
                                       autocomplete="off" class="input">
                            </div>
                        </div>
                        <div class="field has-addons">
                            <div class="control">
                                <div class="button is-static">{{lang('百度地图密钥')}}</div>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="map[bd_key]" value="{{$config['map']['bd_key']??''}}"
                                       placeholder="度地图密钥"
                                       autocomplete="off" class="input">
                            </div>
                        </div>

                    </div>

                    <!--func-->

                    <div class=" tab-item" id="func" v-show="current_idx == 3">

                        <div class="field is-grouped field-body">
                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">{{lang('图片压缩')}}</div>
                                </div>
                                <div class="control">
                                    <label class="input">
                                        <input type="checkbox" name="attach[compress_img]" value="1"
                                               @if(isset($config['attach']['compress_img']) && $config['attach']['compress_img']==1) checked @endif
                                               lay-skin="switch">
                                    </label>

                                </div>
                            </div>
                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">{{lang('压缩宽度')}}</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="attach[width]" value="{{$config['attach']['width']??''}}"
                                           placeholder="请填写数字!" autocomplete="off" class="input">
                                </div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">{{lang('压缩高度')}}</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="attach[height]" value="{{$config['attach']['height']??''}}"
                                           placeholder="压缩高度 请填写数字!" autocomplete="off" class="input">
                                </div>
                            </div>
                        </div>

                        <!--后台设置-->

                        <div class="field is-grouped field-body">
                            <div class="control">
                                <div class="button is-static is-info">{{lang('后台设置')}}</div>
                            </div>

                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">{{lang('默认首页模块')}}</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="admin[index_module]" value="{{$config['admin']['index_module']??'bra_admin'}}"
                                           placeholder="默认首页模块 , 填写模块目录名" autocomplete="off" class="input">
                                </div>
                            </div>
                            <div class="field has-addons">
                                <div class="control">
                                    <div class="button is-static">{{lang('分页大小')}}</div>
                                </div>
                                <div class="control">
                                    <input type="text" name="page_size" value="{{$config['page_size']??'10'}}" placeholder="分页大小" autocomplete="off" class="input">
                                </div>
                            </div>

                        </div>

                        <div class="field has-addons">

                            <div class="control">
                                <label class="button is-static">第三方客服</label>
                            </div>
                            <div class="control">
                                <div class="input-block">
                                    <input type="text" name="kefu_link" value="{{$config['kefu_link'] ??''}}"
                                           placeholder="第三方客服 直接连接" autocomplete="off" class="input">
                                </div>
                            </div>

                            <div class="control">
                                <label class="button is-static is-static">直接连接</label>
                            </div>
                        </div>

                    </div>

                    <!--网站信息-->
                    <div class=" tab-item" id="siteinfo" v-show="current_idx == 4">

                        <div class="field is-grouped field-body">
                            <div class="field has-addons">

                                <div class="control">
                                    <label class="button is-static">备案号</label>
                                </div>
                                <div class="control">
                                    <input type="text" name="beian" value="{{$config['beian'] ??''}}" placeholder="备案号"
                                           autocomplete="off" class="input">
                                </div>
                            </div>
                            <div class="field has-addons">

                                <div class="control">
                                    <label class="button is-static">版权信息</label>
                                </div>
                                <div class="control">
                                    <input type="text" name="copyright" value="{{$config['copyright'] ??''}}" placeholder="版权设置"
                                           autocomplete="off" class="input">
                                </div>
                            </div>

                        </div>

                        <div class="field is-grouped field-body">
                            <div class="field has-addons">

                                <div class="control">
                                    <label class="button is-static">网站联系人</label>
                                </div>
                                <div class="control">
                                    <input type="text" name="contact" value="{{$config['contact'] ??''}}" placeholder="网站联系人"
                                           autocomplete="off" class="input">
                                </div>
                            </div>
                            <div class="field has-addons">

                                <div class="control">
                                    <label class="button is-static">联系微信号</label>
                                </div>
                                <div class="control">
                                    <input type="text" name="wxacount" value="{{$config['wxacount'] ??''}}" placeholder="网站联系人微信号"
                                           autocomplete="off" class="input">
                                </div>
                            </div>

                            <div class="field has-addons">

                                <div class="control">
                                    <label class="button is-static">联系电话</label>
                                </div>
                                <div class="control">
                                    <input type="text" name="mobile" value="{{$config['mobile'] ??''}}" placeholder="网站联系电话"
                                           autocomplete="off" class="input">
                                </div>
                            </div>
                        </div>


                        <div class="field has-addons">
                            <div class="control">
                                <label class="button is-static">联系地址</label>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="address" value="{{$config['address'] ??''}}" placeholder="网站联系地址"
                                       autocomplete="off" class="input">
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <label class="button is-static">广告语1</label>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="add_txt1" value="{{$config['add_txt1'] ??''}}" placeholder="广告语1"
                                       autocomplete="off" class="input">
                            </div>
                        </div>
                        <div class="field has-addons">
                            <div class="control">
                                <label class="button is-static">广告语2</label>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="add_txt2" value="{{$config['add_txt2'] ??''}}" placeholder="广告语2"
                                       autocomplete="off" class="input">
                            </div>
                        </div>

                        <div class="field has-addons">
                            <div class="control">
                                <label class="button is-static">广告语3</label>
                            </div>
                            <div class="control is-expanded">
                                <input type="text" name="add_txt3" value="{{$config['add_txt3'] ??''}}" placeholder="广告语3"
                                       autocomplete="off" class="input">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>
        <div class="field has-addons" style="margin-top:10px">
            <div class="">
                <a type="button" bra-submit bra-filter="*" class="button is-primary" value="submit">保存配置</a>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')

    <script>
        require(['layer', 'jquery', 'Vue'], function (layer, $, Vue) {
            new Vue({
                el : "#bra_app" ,
                data : function (){
                    return {
                        current_idx : 0
                    }
                }
            })
        });
    </script>
    @include("components.bra_admin_post_js")


    @foreach( $field_list as $field)
        {!! $field->form_template !!}
    @endforeach

    @foreach( $field_list as $field)
        {!! $field->form_script !!}
    @endforeach

@endsection
