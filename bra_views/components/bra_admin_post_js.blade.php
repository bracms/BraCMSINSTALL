<script>
    require(['layer', 'jquery', 'bra_form'], function (layer, $, bra_form) {
        bra_form.listen({
            url: "",
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
