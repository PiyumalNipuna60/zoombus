@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.affiliate_code_title'))


@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/Chart.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/Chart.min.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.driver_profit_balance'))
@section('title2', Lang::get('titles.driver_profit_balance2'))

@section('content')
    <div class="row">
        <div class="col-md-4 marg-bottom">
            <!-- Panel Overall Sales -->
            <div class="card card-shadow card-inverse bg-blue-600 white">
                <div class="card-block p-30">
                    <div class="counter counter-lg counter-inverse text-left">
                        <div class="counter-label mb-20">
                            <div class="normal-ge">{{ Lang::get('misc.total_sales') }}</div>
                            <div>{{ Lang::get('misc.total_sales_info') }}</div>
                        </div>
                        <div class="counter-number-group mb-15">

                            <span class="counter-number">{{ $total_sales ?? 0 }}</span>
                            <span class="counter-number-related">GEL</span>
                        </div>
                        @if($vs_prev_year != 0)
                            <div class="counter-label">
                                <div class="progress progress-xs mb-10 bg-blue-800">
                                    <div class="progress-bar progress-bar-info bg-white" aria-valuemax="100"
                                         aria-valuemin="0" style="width: {{ 100-$vs_prev_year }}%;" role="progressbar">
                                        <span class="sr-only">{{ ltrim($vs_prev_year, '-') }}%</span>
                                    </div>
                                </div>
                                <div class="counter counter-sm text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number font-size-14">{{ ltrim($vs_prev_year, '-') }}%</span>
                                        <span class="counter-number-related font-size-14">{{ trans_choice('misc.vs_previous_year', $vs_prev_year ?? 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Panel Overall Sales -->
        </div>

        <div class="col-md-4 marg-bottom">
            <!-- Panel Today's Sales -->
            <div class="card card-shadow card-inverse bg-orange-600 white">
                <div class="card-block p-30">
                    <div class="counter counter-lg counter-inverse text-left">
                        <div class="counter-label mb-20">
                            <div class="normal-ge">{{ Lang::get('misc.monthly_sales') }}</div>
                            <div>{{ Lang::get('misc.monthly_sales_info') }}</div>
                        </div>
                        <div class="counter-number-group mb-10">
                            <span class="counter-number">{{ $monthly_sales ?? 0 }}</span>
                            <span class="counter-number-related">GEL</span>
                        </div>
                        @if($vs_prev_month != 0)
                            <div class="counter-label">
                                <div class="progress progress-xs mb-10 bg-red-800">
                                    <div class="progress-bar progress-bar-info bg-white" aria-valuemax="100" style="width: {{ 100-$vs_prev_month }}%;"
                                         aria-valuemin="0" role="progressbar">
                                        <span class="sr-only">{{ ltrim($vs_prev_month, '-') }}%</span>
                                    </div>
                                </div>
                                <div class="counter counter-sm text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number font-size-14">{{ ltrim($vs_prev_month, '-') }}%</span>
                                        <span class="counter-number-related font-size-14">{{ trans_choice('misc.vs_previous_month',$vs_prev_month ?? 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Panel Today's Sales -->

        </div>

        <div class="col-md-4 marg-bottom">
            <!-- Panel Today's Sales -->
            <div class="card card-shadow card-inverse bg-red-600 white">
                <div class="card-block p-30">
                    <div class="counter counter-lg counter-inverse text-left">
                        <div class="counter-label mb-20">
                            <div class="normal-ge">{{ Lang::get('misc.daily_sales') }}</div>
                            <div>{{ Lang::get('misc.daily_sales_info') }}</div>
                        </div>
                        <div class="counter-number-group mb-10">
                            <span class="counter-number">{{ $daily_sales ?? 0 }}</span>
                            <span class="counter-number-related">GEL</span>
                        </div>
                        @if($vs_prev_day != 0)
                            <div class="counter-label">
                                <div class="progress progress-xs mb-10 bg-red-800">
                                    <div class="progress-bar progress-bar-info bg-white" aria-valuemax="100"
                                         aria-valuemin="0" style="width: {{ 100-$vs_prev_day }}%;" role="progressbar">
                                        <span class="sr-only">{{ ltrim($vs_prev_day, '-') }}%</span>
                                    </div>
                                </div>
                                <div class="counter counter-sm text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number font-size-14">{{ ltrim($vs_prev_day, '-') }}%</span>
                                        <span class="counter-number-related font-size-14">{{ trans_choice('misc.vs_previous_day', $vs_prev_day) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Panel Today's Sales -->

        </div>
    </div>
    <div class="divider2"></div>
    <canvas id="driverProfitsChart"></canvas>
@endsection
