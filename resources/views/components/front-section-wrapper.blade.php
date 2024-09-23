<div class="front-section-wrapper" id="{{ $section_id }}">
    <div class="container">
        <div class="front-section">
            <!--<div class="go-img"></div>-->
            <div class="front-section-img"></div>
            <div class="front-section-title">
                <div class="txt1">{{ $txt1 }}</div>
                <div class="txt2">{{ $txt2 }}</div>
                <div class="txt3">{{ $txt3 }}</div>
            </div>
            <div class="front-section-box">
                <div class="front-section-top clearfix">
                    <div class="front-section-title2">{{ $small_title }}</div>
                </div>
                <div class="front-section-form">
                    <div class="response"></div>
                    @component('components.misc.form')
                        {{ $slot }}
                        @slot('method') POST @endslot
                        @slot('class') form1 clearfix search-home @endslot
                        @slot('form_id') listingForm @endslot
                        @slot('row_inside') @endslot
                        @slot('action') {{ route('listings_search') }} @endslot

                        @component('components.misc.form-group-col')
                            @slot('type') hidden @endslot
                            @slot('hideGroup') @endslot
                            @slot('name') lang @endslot
                            @slot('value') {{ Request::get('locale') ?? config('app.locale') }} @endslot
                        @endcomponent

                        <div class="col-md-6 col-lg-3">
                            {{ $field1 ?? null }}
                        </div>
                        <div class="col-md-6 col-lg-3">
                            {{ $field2 ?? null }}
                        </div>
                        <div class="col-md-6 col-lg-3">
                            {{ $field3 ?? null }}
                        </div>
                        <div class="col-md-6 col-lg-3">
                            {{ $field4 ?? null }}
                        </div>
                        <div class="col-lg-9 col-md-12"></div>
                        <div class="col-md-6 col-lg-3">
                            <button type="submit" class="btn-form1-submit">{{ Lang::get('misc.search') }}</button>
                        </div>
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
</div>
