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
<form class="seiger-mail-form" id="form" name="form" method="post" enctype="multipart/form-data" action="{!!$url!!}&get=periodic" onsubmit="documentDirty=false;">
    <div class="form-row">
        <div class="row-col col-8 pr-4" style="padding-right:48px !important;">
            <div class="row-col col-12 p-0">
                <div class="row form-row align-items-center seiger-mail-row">
                    <div class="col-auto col-title-7">
                        <label for="alias" class="warning mb-0 seiger-input-title">Тема листа</label>
                        <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                    </div>
                    <div class="input-group col">
                        <input name="periodic[subject]" value="{!!config('seiger.settings.sMailer.periodic.subject', '')!!}" type="text" class="form-control w-70 seiger-input" onchange="documentDirty=true;">
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
                        <input id="cover" name="periodic[cover]" value="{!!config('seiger.settings.sMailer.periodic.cover', '')!!}" type="text" class="form-control seiger-input rounded-left border-right-0" onchange="documentDirty=true;">
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
                        <input name="periodic[slogan]" value="{!!config('seiger.settings.sMailer.periodic.slogan', '')!!}" type="text" class="form-control seiger-input w-70 rounded" onchange="documentDirty=true;">
                    </div>
                </div>
            </div>
            <div class="row-col col-12 p-0">
                <div class="row form-row align-items-center seiger-mail-row">
                    <div class="col-auto col-title-7">
                        <label for="alias" class="warning mb-0 seiger-input-title">Заголовок для історій</label>
                        <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                    </div>
                    <div class="input-group col">
                        <input name="periodic[books_title]" value="{!!config('seiger.settings.sMailer.periodic.books_title', '')!!}" type="text" class="form-control w-70 seiger-input rounded" onchange="documentDirty=true;">
                    </div>
                </div>
            </div>
            <div class="row-col col-12 p-0">
                <div class="row form-row align-items-center ">
                    <div class="col-auto col-title-7">
                        <label for="alias" class="warning mb-0 seiger-input-title">Заголовок для порад</label>
                        <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                    </div>
                    <div class="input-group col">
                        <input name="periodic[articles_title]" value="{!!config('seiger.settings.sMailer.periodic.articles_title', '')!!}" type="text" class="form-control w-70 seiger-input rounded" onchange="documentDirty=true;">
                    </div>
                </div>
            </div>
        </div>
        <div class="row-col col-4 pl-4 border-left" style="padding-left:48px !important;">
            <div class="row form-row align-items-center seiger-mail-row">
                <div class="col-auto col-title-7">
                    <label for="alias" class="warning mb-0 seiger-input-title">День тижня відправки</label>
                    <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                </div>
                @php($day = config('seiger.settings.sMailer.periodic.day', 'Monday'))
                <div class="input-group col">
                    <select class="custom-select" id="inputGroupSelect01" name="periodic[day]">
                        <option value="Monday" @if($day == 'Monday')selected @endif>Понеділок</option>
                        <option value="Tuesday" @if($day == 'Tuesday')selected @endif>Вівторок</option>
                        <option value="Wednesday" @if($day == 'Wednesday')selected @endif>Середа</option>
                        <option value="Thursday" @if($day == 'Thursday')selected @endif>Четверг</option>
                        <option value="Friday" @if($day == 'Friday')selected @endif>Пʼятниця</option>
                        <option value="Saturday" @if($day == 'Saturday')selected @endif>Субота</option>
                        <option value="Sunday" @if($day == 'Sunday')selected @endif>Неділя</option>
                    </select>
                </div>
            </div>
            <div class="row form-row align-items-center seiger-mail-row">
                <div class="col-auto col-title-7">
                    <label for="alias" class="warning mb-0 seiger-input-title">Час відправки</label>
                    <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                </div>
                <div class="input-group col">
                    <input id="timepicker" name="periodic[time]" value="{!!config('seiger.settings.sMailer.periodic.time', '')!!}" pattern="[0-2][0-9]:[0-5][0-9]" type="text"  class="form-control seiger-input-time input-small js-time">
                </div>
            </div>
            <a href="{!!$url!!}&get=periodic-preview" target="_blank" type="button" class="btn btn-sm mb-4 seiger-btn seiger-primary-btn">Попередній перегляд</a>
            <div class="row form-row align-items-center ">
                <input type="checkbox" id="publishedcheck" name="publishedcheck" aria-label="Checkbox for following text input" value="" onchange="documentDirty=true;" onclick="changestate(document.form.published);" @if(config('seiger.settings.sMailer.periodic.published', 0) == 1) checked @endif>
                <input type="hidden" id="published" name="periodic[published]" value="{{config('seiger.settings.sMailer.periodic.published', 0)}}" onchange="documentDirty=true;">
                <div class="col-auto col-title-7">
                    <label for="alias" class="warning mb-0 seiger-input-title">Увімкнути розсилку</label>
                    <!-- <i class="fa fa-question-circle" data-tooltip=""></i> -->
                </div>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

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
