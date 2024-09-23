<div class="top-wrapper">
    <div class="top1-wrapper">
        <div class="container">
            <div class="top1 clearfix">
                @include('components.header.contact-and-socials')
                <div class="divider divider-sl"></div>
                @component('components.header.language', ['current_locale' => $current_locale]) @endcomponent
                @if(Auth::check())
                    @component('components.header.cart', ['items' => $cart_items ?? []])
                        @slot('total') {{ array_sum(array_column(array_column($cart_items ?? [], 'sales'), 'price')) }} @endslot
                        @slot('current_currency_key') {{ $current_currency_key ?? 'GEL' }} @endslot
                    @endcomponent
                    @component('components.header.notifications', ['items' => $notifications ?? [], 'number_of_items' => $new_notifications ?? 0, 'current_locale' => $current_locale])
                    @endcomponent
                    @include('components.header.auth.authorized')
                @else
                    <div class="top-buttons">
                        @include('components.header.auth.login')
                        <span class="top-buttons-divider">/</span>
                        @include('components.header.auth.register')
                    </div>
                @endif
                <div class="divider divider-tc"></div>
                @component('components.header.currency', ['currencies' => $currencies, 'current_currency' => $current_currency]) @endcomponent
            </div>
        </div>
    </div>
    <div class="top2-wrapper">
        <div class="container">
            <div class="top2 clearfix">
                @include('components.header.logo')
                @component('components.header.menu', ['faqs' => $faqs ?? []])
                    @isset($isDriver)
                        @slot('isDriver') @endslot
                    @endisset
                    @isset($isPartner)
                        @slot('isPartner') @endslot
                    @endisset
                @endcomponent
            </div>
        </div>
    </div>
</div>
