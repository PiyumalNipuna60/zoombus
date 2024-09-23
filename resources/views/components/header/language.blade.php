<div class="language dropdown">
    <a class="dropdown-toggle" href="#" role="button" id="dropdownLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{ URL::asset('images/flag-'.$current_locale .'.jpg') }}"
             alt="" class="img-fluid"><span>
            {{ LaravelLocalization::getSupportedLocales()[$current_locale]['name'] }}
        </span>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownLanguage">
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" @if(Request::getMethod() == 'POST') data-resubmit="listingForm" @endif href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                <img src="{{ URL::asset('images/flag-'.$localeCode.'.jpg') }}" alt="{{ $properties['name'] }}" class="img-fluid">{{ $properties['name'] }}
            </a>
    @endforeach
    </div>
</div>
