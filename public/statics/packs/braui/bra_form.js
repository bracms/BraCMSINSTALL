/* bra_form for jquery */
;!function (window, bra_form , $ ) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("bra_form", ["jquery"], function ($) {
            "use strict";
            $.extend({
                bra_form: bra_form
            });
            return bra_form;
        });
    } else {
        $.extend({
            bra_form: bra_form
        });
    }
}(window , function () {
    if(typeof jQuery === 'undefined'){
        throw new Error("BraForm Need Jquery");
    }
    return {
        configs : {

        } ,
        default_config: {
            el: '.bra-form',
            url: "",
            verify: false,
            before_submit: false,
            success: false,
            error: false,
            error_class: 'is-danger',
            finish: false,
            filter: '',
            form_elem: false //当前所在表单域
            , fields: {} ,
            fail : function (resp) {
                console.log(resp)
            },
            errors: []
        },
        /**
         * 获取表单值
         * @returns {string}
         */
        getValue: function (form_config) {
            var nameIndex = {} //数组 name 索引
                , field = {}
                , fieldElems = form_config.form_elem.find('input,select,textarea'); //获取所有表单域

            console.log(form_config.form_elem)
            $.each(fieldElems, function (_, item) {
                item.name = (item.name || '').replace(/^\s*|\s*&/, '');

                if (!item.name) return;

                //用于支持数组 name
                if (/^.*\[\]$/.test(item.name)) {
                    var key = item.name.match(/^(.*)\[\]$/g)[0];
                    nameIndex[key] = nameIndex[key] | 0;
                    item.name = item.name.replace(/^(.*)\[\]$/, '$1[' + (nameIndex[key]++) + ']');
                }

                if (/^checkbox|radio$/.test(item.type) && !item.checked) return;
                field[item.name] = item.value;
            });

            return field;
        },

        /***
         * @param e event src
         * @param form_config
         * @returns {[]}
         */
        submit: function (e , form_config) {
            if(form_config.trigger.data('action') !== "reset"){
                e.preventDefault();
            }

            var that = this, errors = [], form_fields;
            form_fields = form_config.form_elem.find('*[bra-verify]'); //获取需要校验的元素

            //获取当前表单值
            form_config.fields = this.getValue(form_config);

            if (form_config.verify) {
                var verify = form_config.verify.configs;
                //开始校验
                $.each(form_fields, function (_, item) {

                    // $(item).removeClass('is-danger');

                    var othis = $(this), vers = othis.attr('bra-verify').split('|'), value = othis.val();
                    othis.removeClass(form_config.error_class); //移除警示样式
                    //遍历元素绑定的验证规则
                    $.each(vers, function (_, thisVer) {
                        var is_error //是否匹配错误规则
                            , errorText = '' //错误提示文本
                            , isFn = typeof verify[thisVer] === 'function';

                        //匹配验证规则
                        if (verify[thisVer]) {
                            is_error = isFn ? errorText = verify[thisVer](value, item) : !verify[thisVer][0].test(value);

                            if (is_error) {
                                errorText = errorText || verify[thisVer][1];
                                //trigger error event

                                if (typeof form_config.error === 'function') {
                                    othis.addClass(form_config.error_class);
                                    form_config.error({
                                        form_name: $(item).attr('name'),
                                        msg: errorText,
                                        val: value
                                    });
                                }

                            }

                        }

                        if (is_error) {
                            errors.push({
                                form_name: $(item).attr('name'),
                                msg: errorText,
                                val: value
                            });
                        }
                    });

                });
            }

            if (errors.length > 0) {
                return form_config.errors = errors;
            } else {
                form_config.errors = [];
            }

            if (typeof form_config.before_submit === 'function') {
                form_config.before_submit(form_config.fields, function (before_data) {
                    before_data = before_data ? before_data : form_config.fields;
                    that.bra_submit(before_data , form_config);
                });
            } else {
                this.bra_submit(form_config.fields , form_config)
            }
        },

        bra_submit: function (params , form_config) {
            $.post(form_config.url, params, function (ret_data) {
                if (ret_data.code === 1) {//服务器处理成功
                    if (typeof form_config.success === 'function') {
                        form_config.success(ret_data, form_config);
                    }
                } else {
                    if (typeof form_config.error === 'function') {
                        form_config.error(ret_data, form_config);
                    }
                }
            }, 'json').fail(function (resp) {
                if (typeof form_config.fail === 'function') {
                    form_config.fail(resp , form_config);
                }
            }).always(function (ret_data) {
                if (typeof form_config.finish === 'function') {
                    form_config.finish(ret_data , form_config);
                }
            });
        },

        /**
         * if you don't use a form id in config,use * as filter
         * @param form_config
         */
        listen: function (form_config) {
            var that = this;
            if(!form_config.id){
                this.configs["*"] = $.extend({}, this.default_config, form_config , {id : "*"});
            }else{
                this.configs[form_config.id] = $.extend({}, this.default_config, form_config );
            }

            $("[bra-submit]").unbind("click").on('click', function (e) {

                var form_id = that.filter = $(this).attr('bra-filter');
                form_config = that.configs[form_id];
                if(!form_config){
                    console.log(form_id , that.configs)
                }
                form_config.trigger = $(this)
                if(form_config.id === "*"){
                    form_config.form_elem = $("form"+form_config.el+":first");
                }else{
                    form_config.form_elem = $(form_config.el + "[bra-id='" + form_config.id + "']");
                }
                that.submit(e , that.configs[form_config.id])
            });
        }
    };
}() , jQuery);
