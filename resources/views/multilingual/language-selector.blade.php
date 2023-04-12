@if (isset($isModelTranslatable) && $isModelTranslatable)
    <div class="language-selector">
        <div class="btn-group btn-group-sm" role="group" data-toggle="buttons">
            @foreach(config('adminpanel.multilingual.locales') as $lang)
            <input type="radio"
                   class="btn-check {{ ($lang ===  Cookie::get('adminFormLocale',config('adminpanel.multilingual.default'))) ? "_current" : "" }}"
                   name="i18n_selector"
                   id="{{$lang}}"
                   autocomplete="off"
                   @checked($lang === Cookie::get('adminFormLocale',config('adminpanel.multilingual.default')))>
            <label class="btn btn-outline-primary" for="{{$lang}}" >{{ strtoupper($lang) }}</label>
            @endforeach
        </div>
    </div>

@endif
