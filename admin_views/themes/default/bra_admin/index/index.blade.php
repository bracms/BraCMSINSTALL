@extends("themes.default.public.crm_layout")

@section('title', $site_config['site_name'] . ' - 后台中心')


@section('logo_area')
    <span class="logo-text">{{$site_config['site_name']}}<span></span></span>
@endsection


@section('user_act_section')

    <a target="_blank" href="/">
        <i class="fas fa-home-lg"></i>
    </a>
    <a bra-mini="iframe" data-href="{{ make_url('bra_admin/admin_api/change_pass') }}"><i class="fas fa-key"></i></a>
    <a bra-mini="load" data-href="{{ make_url('bra_admin/admin_api/clear_cache') }}">
        <i class="fas fa-magic-wand-sparkles has-text-danger"></i>
    </a>

@endsection
