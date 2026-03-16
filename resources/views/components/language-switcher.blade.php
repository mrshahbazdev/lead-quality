<div class="lang-selector">
    <select onchange="window.location.href='/lang/' + this.value" style="background: var(--dark-2); color: white; border: 1px solid var(--glass-border); padding: 0.3rem 0.5rem; border-radius: 0.5rem; font-size: 0.8rem; cursor: pointer;">
        @php
            $locales = [
                'en' => '🇺🇸 EN',
                'es' => '🇪🇸 ES',
                'fr' => '🇫🇷 FR',
                'de' => '🇩🇪 DE',
                'zh' => '🇨🇳 ZH',
                'ja' => '🇯🇵 JA',
                'pt' => '🇵🇹 PT',
                'ru' => '🇷🇺 RU',
                'ar' => '🇸🇦 AR',
                'hi' => '🇮🇳 HI'
            ];
            $currentLocale = app()->getLocale();
        @endphp
        @foreach($locales as $code => $name)
            <option value="{{ $code }}" {{ $currentLocale == $code ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
</div>
