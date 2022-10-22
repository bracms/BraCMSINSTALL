@extends('themes.default.public.base_layout')
@section('main')
    <div class="buttons">
        @foreach($icons[1] as $icon)
            <button class="button" data-clipboard-text="fas {{$icon}}">
    <span class="icon is-small">
      <i class="fas {{$icon}}"></i>
    </span>
                <span>{{$icon}}</span>
            </button>
        @endforeach
    </div>

@endsection
@section('requirejs')

@endsection
@section('footer_js')
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>
    <script>
        new ClipboardJS('.button');
    </script>
@endsection

@section('bra_init_js')
@endsection
