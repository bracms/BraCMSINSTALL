@extends("themes.default.public.base_layout")

@section('table_form')
    <div class="notification is-success is-light">
        自定义字段选项
    </div>
@endsection

@section('main')
    <div class="columns is-multiline ">
        @foreach($fields as $field)
            @php
                $map = [];
                $model = $field->BL_model🩱table_name🩱table_name();
                if(!$model){
                    continue;
                }
                $map['model_id'] =  $model['id'];
                $map['field_name'] = $field['field_name'];
                $vars = "field_name={field_name}&model_id={model_id}";
            @endphp
            <div class="card column is-3" style="margin: 15px;">


                <a class="bra-cell" bra-mini="iframe" href="{{ build_back_link(7 , $vars , $map ) }}">
                    <div class="bra-cell__bd">{{ $field['title'] }} ({{ $model['title']}})</div>
                    <div class="bra-cell__ft"></div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
