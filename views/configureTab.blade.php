<style>.input-group .tox-tinymce{width:100%;}</style>
<form id="form" name="form" method="post" enctype="multipart/form-data" action="{!!$url!!}&get=configure" onsubmit="documentDirty=false;">
    <div class="row form-row">
        <div class="col mb-4">
            <div class="col-12 col-title-7 mb-3">
                <label for="alias" class="warning mb-0 seiger-input-title">Текст у підвалі</label>
            </div>
            <div class="input-group">
                <textarea id="footer_text" name="config[footer_text]" class="form-control col-12" onchange="documentDirty=true;">{!!config('seiger.settings.sMailer.config.footer_text', '')!!}</textarea>
            </div>
        </div>
        <div class="row-col col-12 mb-3">
            <div class="row form-row seiger-inputs-group">
                <div class="col-12 col-title-7 mb-3">
                    <label class="warning mb-0 seiger-input-title">Іконки</label>
                </div>
                @php($icons = config('seiger.settings.sMailer.config.icons', []))
                @foreach($icons as $idx => $icon)
                    <div class="input-group col-12 mb-3">
                        <div class="input-group col-6 ">
                            <input id="icons{{$idx}}" name="config[icons][file][]" value="{!!config('seiger.settings.sMailer.config.icons.'.$idx.'.file', '')!!}" type="text" class="form-control seiger-input rounded-left border-right-0" onchange="documentDirty=true;">
                            <input class="form-control rounded-right seiger-input-btn" type="button" value="Вибрати файл" onclick="BrowseServer('icons{{$idx}}')">
                        </div>
                        <div class="input-group col-6 align-items-center">
                            <div class="col-auto col-title-7">
                                <label class="warning mb-0 seiger-input-title">Посилання</label>
                            </div>
                            <input name="config[icons][link][]" value="{!!config('seiger.settings.sMailer.config.icons.'.$idx.'.link', '')!!}" class="form-control rounded seiger-input" type="text" onchange="documentDirty=true;">
                            <button type="button" class="btn btn-sm ml-3 seiger-sub-remove border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                    <rect x="13.1328" y="0.558868" width="1.80855" height="17.9902" transform="rotate(45 13.1328 0.558868)" fill="#EF4B67"/>
                                    <rect x="0.412109" y="1.8378" width="1.80855" height="17.9902" transform="rotate(-45 0.412109 1.8378)" fill="#EF4B67"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button id="addIcon" type="button" class="btn btn-sm seiger-btn seiger-primary-btn" onclick="addIcons();">Додати іконку</button>
    </div>
    <div class="row-col col-12 p-0">
        <div class="row form-row align-items-center seiger-mail-row">
            <div class="col-auto col-title-7">
                <label class="warning mb-0 seiger-input-title">Юридична іформація</label>
            </div>
            <div class="input-group col">
                <input type="text" name="config[legal]" value="{!!config('seiger.settings.sMailer.config.legal', '')!!}" class="form-control w-70 rounded seiger-input" onchange="documentDirty=true;">
            </div>
        </div>
    </div>
    <div class="row-col col-12 p-0">
        <div class="row form-row align-items-center ">
            <div class="col-auto col-title-7">
                <label for="alias" class="warning mb-0 seiger-input-title">Текст про відписку</label>
            </div>
            <div class="input-group col">
                <input type="text" name="config[unsubscribe_text]" value="{!!config('seiger.settings.sMailer.config.unsubscribe_text', '')!!}" class="form-control seiger-input w-70 rounded" onchange="documentDirty=true;">
            </div>
        </div>
    </div>
    <input name="config[site_url]" value="{{evo()->getConfig('site_url', '/')}}" type="hidden"/>
</form>
@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#form');">
                <i class="fa fa-save"></i><span>@lang('global.save')</span>
            </a>
        </div>
    </div>
    <div class="hidden-elemens" style="display: none">
        <div class="input-group col-12 mb-3 icon-item">
            <div class="input-group col-6 ">
                <input id="icons777" name="config[icons][file][]" value="" type="text" class="form-control seiger-input rounded-left border-right-0" onchange="documentDirty=true;">
                <input class="form-control rounded-right seiger-input-btn" type="button" value="Вибрати файл" onclick="BrowseServer('icons777')">
            </div>
            <div class="input-group col-6 align-items-center">
                <div class="col-auto col-title-7">
                    <label class="warning mb-0 seiger-input-title">Посилання</label>
                </div>
                <input name="config[icons][link][]" value="" class="form-control rounded seiger-input" type="text" onchange="documentDirty=true;">
                <button type="button" class="btn btn-sm ml-3 seiger-sub-remove border-0" onclick="this.closest('.icon-item').remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                        <rect x="13.1328" y="0.558868" width="1.80855" height="17.9902" transform="rotate(45 13.1328 0.558868)" fill="#EF4B67"/>
                        <rect x="0.412109" y="1.8378" width="1.80855" height="17.9902" transform="rotate(-45 0.412109 1.8378)" fill="#EF4B67"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <script>
        function addIcons() {
            let counts=document.querySelectorAll('.icon-item').length;
            let blank_element = document.querySelector('.hidden-elemens .icon-item');
            let elemnt=blank_element.innerHTML.replaceAll('icons777', 'icons'+counts);
            document.getElementById('addIcon').insertAdjacentHTML('beforebegin', "<div class=\"input-group col-12 mb-3 icon-item\">"+elemnt+"</div>");
            documentDirty=true;
        }
    </script>
@endpush
