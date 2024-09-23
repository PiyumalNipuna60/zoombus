<div class="panel panel-default">
    <div class="panel-body">
        <h3><i class="fa fa-credit-card"></i> {{ Lang::get('admin.payout_methods') }}</h3>
        <p>{{ Lang::get('admin.payout_methods_text') }}</p>
    </div>
    <div class="panel-body list-group">
        @foreach($methods as $md)
            <div class="list-group-item">
                <i class="fa fa-2x @if($md['type'] == 1) fa-cc @elseif($md['type'] == 2) fa-cc-paypal @else fa-bank @endif"></i>
                @if($md['type'] == 1) {{ $md['card_number'] }}
                @elseif($md['type'] == 2) {{ $md['paypal_email'] }}
                @elseif($md['type'] == 3) {{ Lang::get('admin.bank_info') }} <br/><br/>
                <h6>{{ Lang::get('admin.beneficiary') }}:</h6>
                <p>
                    {{ $md['your_name'] }}
                </p>
                <h6>{{ Lang::get('admin.bank_of_beneficiary') }}y:</h6>
                <p>
                    {{ $md['bank_name'] }}
                </p>
                <h6>{{ Lang::get('admin.acc_number_iban') }}:</h6>
                <p>
                    {{ $md['account_number'] }}
                </p>
                <h6>{{ Lang::get('admin.swift') }}:</h6>
                <p>
                    {{ $md['swift'] }}
                </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
