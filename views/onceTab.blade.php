<style>
    .seiger-input-title {
        color: var(--text-text-middle, #63666B);
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        padding-right: 5px;
    }
    .form-control.seiger-input {
        color: var(--text-text-base, #0D0D0D);
        height: 42px;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        padding: 16px 12px;
        border-radius: 6px !important;
    }
    .form-control.seiger-input-btn {
        border-radius: 0px 6px 6px 0px !important;
        border-top: 1px solid #BBB;
        border-right: 1px solid #BBB;
        border-bottom: 1px solid #BBB;
        background-color: var(--secondary-gray, #EAEAEA) !important;
        color: var(--text-text-base, #0D0D0D);
        font-size: 15px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        max-width: 123px;
    }
    .form-control.seiger-input.rounded-left {
        border-radius: 6px 0 0 6px !important;
    }
    .form-row.row {
        margin-right: 0 !important;
    }
    .seiger-primary-btn {
        border-radius: 2px;
        background: var(--brand-storiya-green, #009891);
        margin: 0 0 32px 0;
        padding: 16px 24px;
        color: var(--text-text-light, #FFF);
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
    }
    .seiger-mail-row {
        margin-bottom: 32px;
    }
    .seiger-input-time {
        border-radius: 6px;
        border: 1px solid #BBB;
        background: var(--text-text-light, #FFF);
        max-width: 65px;
        height: 42px;
        color: var(--text-text-base, #0D0D0D);
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
    }
    .seiger-mail-form {
        padding: 8px;
    }
</style>
<form class="seiger-mail-form" id="form" name="form" method="post" enctype="multipart/form-data" action="{!!$url!!}&get=once" onsubmit="documentDirty=false;">
    <div class="form-row">
        <div class="row-col col-8 pr-4" style="padding-right:48px !important;">
            <div class="row-col col-12 p-0">
                <div class="row form-row align-items-center seiger-mail-row">
                    <div class="col-auto col-title-7">
                        <label for="alias" class="warning mb-0 seiger-input-title">Тема листа</label>
                        <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                    </div>
                    <div class="input-group col">
                        <input name="once[subject]" value="{!!config('seiger.settings.sMailer.once.subject', '')!!}" type="text" class="form-control w-70 seiger-input" onchange="documentDirty=true;">
                    </div>
                </div>
            </div>
            <div class="row-col col-12 p-0">
                <div class="row form-row align-items-center seiger-mail-row">
                    <div class="col-auto col-title-7">
                        <label for="alias" class="warning mb-0 seiger-input-title">Зображення у шапці</label>
                        <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                    </div>
                    <div class="input-group col">
                        <input id="cover" name="once[cover]" value="{!!config('seiger.settings.sMailer.once.cover', '')!!}" type="text" class="form-control seiger-input rounded-left border-right-0" onchange="documentDirty=true;">
                        <input class="form-control rounded-right seiger-input-btn" type="button" value="Вибрати файл" onclick="BrowseServer('cover')">
                    </div>
                </div>
            </div>
            <div class="row-col col-12 p-0">
                <div class="row form-row align-items-center seiger-mail-row">
                    <div class="col-auto col-title-7">
                        <label for="alias" class="warning mb-0 seiger-input-title">Слоган у шапці</label>
                        <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                    </div>
                    <div class="input-group col">
                        <input name="once[slogan]" value="{!!config('seiger.settings.sMailer.once.slogan', '')!!}" type="text" class="form-control seiger-input w-70 rounded" onchange="documentDirty=true;">
                    </div>
                </div>
            </div>
        </div>
        <div class="row-col col-4 pl-4 border-left" style="padding-left:48px !important;">
            <div class="row form-row align-items-center seiger-mail-row">
                <div class="col-auto col-title-7">
                    <label for="alias" class="warning mb-0 seiger-input-title">Дата та час відправки</label>
                </div>
                <div class="input-group date" id="datetimepicker1" data-td-target-input="nearest">
                    <input name="once[datetime]" value="{!!config('seiger.settings.sMailer.once.datetime', '')!!}" type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                    <div class="input-group-append" data-target="#datetimepicker1" data-td-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
            <a href="{!!$url!!}&get=once-preview" target="_blank" type="button" class="btn btn-sm mb-4 seiger-btn seiger-primary-btn">Попередній перегляд</a>
            <div class="row form-row align-items-center ">
                <input type="checkbox" id="publishedcheck" name="publishedcheck" aria-label="Checkbox for following text input" value="" onchange="documentDirty=true;" onclick="changestate(document.form.published);" @if(config('seiger.settings.sMailer.once.published', 0) == 1) checked @endif>
                <input type="hidden" id="published" name="once[published]" value="{{config('seiger.settings.sMailer.once.published', 0)}}" onchange="documentDirty=true;">
                <div class="col-auto col-title-7">
                    <label for="alias" class="warning mb-0 seiger-input-title">Увімкнути розсилку</label>
                    <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                </div>
            </div>
        </div>
        <div class="col mb-4">
            <div class="col-12 col-title-7 mb-3">
                <label for="alias" class="warning mb-0 seiger-input-title">Контент</label>
                <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
            </div>
            <div class="col" id="content-blocks">
                @if(is_array($content = evo()->invokeEvent('sMailerOnceContentRender')))
                    {!!implode('', $content)!!}
                @else
                    <textarea id="content" name="once[content]" class="form-control col-12" onchange="documentDirty=true;">{!!config('seiger.settings.sMailer.once.content', '')!!}</textarea>
                @endif
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<!-- Popperjs -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<!-- Tempus Dominus JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>
<!-- Tempus Dominus Styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">
<style>
    #timepicker {
        display: none;
    }
    .tempus-dominus-widget.dark {
        background-color: #222222 ;
        color:rgb(24, 24, 24);
    }
    .tempus-dominus-widget.dark .date-container div {
        color:rgb(24, 24, 24);
    }
    .tempus-dominus-widget.dark .date-container-days div:not(.no-highlight).active {
        background: #009891;
        color: #fff;
    }
    .tempus-dominus-widget.dark .date-container-days div:not(.no-highlight):hover {
        color: #fff;
        background: #009891;
    }
    .tempus-dominus-widget .toolbar div {
        background: #009891;
        color: #fff;
    }
    .tempus-dominus-widget.dark .date-container-days .dow, .tempus-dominus-widget.dark .date-container-days div:not(.no-highlight).old{
        color:rgba(13, 13, 13, 0.6);
    }

</style>
<script>
    $("#timepicker").datetimepicker({
        format : "HH:mm",
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-caret-up",
            down: "fa fa-caret-down",
            previous: "fa fa-chevron-left",
            next: "fa fa-chevron-right",
            today: "fa fa-clock-o",
            clear: "fa fa-trash-o"
        },
    });
    const picker = new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'),{
        localization: {
            locale: 'uk',
            format: 'dd.MM.yyyy HH:mm'
        }
    });
</script>

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#form');">
                <i class="fa fa-save"></i><span>@lang('global.save')</span>
            </a>
        </div>
    </div>
@endpush
