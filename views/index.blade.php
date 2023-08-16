@extends('manager::template.page')
@section('content')
    <h1><i class="@lang('sSeo::global.icon')" data-tooltip="@lang('sSeo::global.description')"></i>@lang('sSeo::global.title')</h1>
    <div class="sectionBody">
        <div class="tab-pane" id="resourcesPane">
            <script>tpResources = new WebFXTabPane(document.getElementById('resourcesPane'), false);</script>
            <div class="tab-page configureTab" id="configureTab">
                <h2 class="tab">
                    <a href="">
                        <span><i class="@lang('sSeo::global.configure_icon')" data-tooltip="@lang('sSeo::global.configure_help')"></i> Налаштування</span>
                    </a>
                </h2>
                <script>tpResources.addTabPage(document.getElementById('configureTab'));</script>
                <div class="container container-body">
                    @include('sMailer::configureTab')
                </div>
            </div>
            <div class="tab-page periodicTab" id="periodicTab">
                <h2 class="tab">
                    <a href="">
                        <span><i class="@lang('sSeo::global.configure_icon')" data-tooltip="@lang('sSeo::global.configure_help')"></i> Періодична розсилка</span>
                    </a>
                </h2>
                <script>tpResources.addTabPage(document.getElementById('periodicTab'));</script>
                <div class="container container-body">
                    @include('sMailer::periodicTab')
                </div>
            </div>
            <div class="tab-page onceTab" id="onceTab">
                <h2 class="tab">
                    <a href="">
                        <span><i class="@lang('sSeo::global.configure_icon')" data-tooltip="@lang('sSeo::global.configure_help')"></i> Одноразова розсилка</span>
                    </a>
                </h2>
                <script>tpResources.addTabPage(document.getElementById('onceTab'));</script>
                <div class="container container-body">
                    @include('sMailer::onceTab')
                </div>
            </div>
            <div class="tab-page subscribersTab" id="subscribersTab">
                <h2 class="tab">
                    <a href="">
                        <span><i class="@lang('sSeo::global.configure_icon')" data-tooltip="@lang('sSeo::global.configure_help')"></i> Підписники</span>
                    </a>
                </h2>
                <script>tpResources.addTabPage(document.getElementById('subscribersTab'));</script>
                <div class="container container-body">
                    @include('sMailer::subscribersTab')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts.bot')
    <script>function saveForm(selector){$(selector).submit()}</script>
    <style>
        #copyright{position:fixed;bottom:0;right:0;background-color:#0057b8;padding:3px 7px;border-radius:5px;}
        #copyright img{width:9em;}
    </style>
    <div id="copyright"><a href="https://seigerit.com/" target="_blank"><img src="{{evo()->getConfig('site_url', '/')}}assets/site/seigerit-yellow.svg"/></a></div>
@endpush
