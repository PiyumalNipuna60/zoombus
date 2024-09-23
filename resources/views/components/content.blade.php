<div id="content">
    <div class="container">
        @isset($title1)
            <div class="title1">{{ $title1 }}</div>
        @endisset
        @isset($title2)
            <div class="title2">
                {{ $title2 }}
                @isset($videoUrl)
                    <br>
                    <a href="{{ $videoUrl }}" class="fancybox view_video">
                        {{ Lang::get('misc.view_video') }} <i class="fa fa-video-camera"></i>
                    </a>
                @endisset
            </div>
        @endisset
        @if(isset($isprofile) && Auth::check())
            @component('components.user-menu')
                @isset($isDriver)
                    @slot('isDriver') @endslot
                @endisset
                @isset($isPartner)
                    @slot('isPartner') @endslot
                @endisset
            @endcomponent
            @isset($title3)
                <div class="title5">{{ $title3 }}</div>
            @endisset
            @isset($title4)
                <div class="title2">{{ $title4 }}</div>
            @endisset
        @endif
        {{ $slot }}
    </div>
</div>
