@extends('manager::template.page')
@section('content')
    <h1 class="seiger__module-title"><i class="@lang('sMailer::global.mailer_icon')" data-tooltip="@lang('sMailer::global.mailer_help')"></i>@lang('sMailer::global.mailer')</h1>
    <div class="sectionBody">
        <div class="tab-pane" id="resourcesPane">
            <script>tpResources = new WebFXTabPane(document.getElementById('resourcesPane'), false);</script>
            @foreach($tabs as $tab)
                <div class="tab-page {{$tab}}Tab" id="{{$tab}}Tab">
                    <h2 class="tab">
                        <a href="{!!$url!!}&get={{$tab}}">
                            <span><i style="margin-right:0.5em;font-size:0.875rem;" class="@lang('sMailer::global.'.$tab.'_icon')" data-tooltip="@lang('sMailer::global.'.$tab.'_help')">@if(__('sMailer::global.'.$tab.'_icon') == 'svg')@lang('sMailer::global.'.$tab.'_svg_icon')@endif</i>
                                @lang('sMailer::global.'.$tab)
                            </span>
                        </a>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('{{$tab}}Tab'));</script>
                    <div class="container container-body">
                        @if($get == $tab)
                            @include('sMailer::'.$tab.'Tab')
                        @endif
                    </div>
                </div>
            @endforeach
            <script>tpResources.setSelectedTab('{{$get}}Tab');</script>
        </div>
    </div>
@endsection
@push('scripts.top')
    @include('sMailer::partials.style')
@endpush
@push('scripts.bot')
    {!!$editor!!}
    <script>
        function saveForm(selector){$(selector).submit()}
        function changestate(el){if(parseInt(el.value)===1){el.value=0}else{el.value=1;}documentDirty=true;}
    </script>
    <div id="copyright"><a href="https://seigerit.com/" target="_blank"><img src="{{evo()->getConfig('site_url', '/')}}assets/site/seirgerit-white.svg"/></a></div>
@endpush
