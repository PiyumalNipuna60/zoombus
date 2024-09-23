<div class="panel panel-default">
    <div class="panel-body">
        <h3><span class="fa fa-globe"></span> {{ Lang::get('admin_titles.registered_routes') }}</h3>
    </div>
    @foreach($routes as $rt)
        <div class="panel-body">
            <h3>
                <span class="fa fa-map-marker"></span>
                {{ $rt['cities_from']['translated']['name'] }} <i class="fa fa-long-arrow-right"></i>
                {{ $rt['cities_to']['translated']['name'] }} <span class="badge badge-primary">{{ $rt['cities_from']['code'] }}{{ $rt['id'] }}</span>
            </h3>
            <p>{{ $rt['address_from']['translated']['name']}} <i class="fa fa-long-arrow-right"></i> {{ $rt['address_to']['translated']['name'] }}</p>
        </div>
    @endforeach
</div>
