function initMap() {
    let already_open = $('.already-open');
    if(already_open.length) {
        let directionsService = new google.maps.DirectionsService;
        let directionsDisplay = new google.maps.DirectionsRenderer;
        let map = new google.maps.Map(already_open[0], {
            zoom: 7,
            center: {lat: 0, lng: 0}
        });
        directionsDisplay.setMap(map);
        let from = already_open.parent().find('.from_city').html(),
            to = already_open.parent().find('.to_city').html();
        calculateAndDisplayRoute(directionsService, directionsDisplay, from, to);
    }
    $(document).on('click', '.ticket-details-info .details a', function (e) {
        e.preventDefault();
        $(this).parents('.ticket-details-info').toggleClass('opened');
        $(this).parents('.ticket-details-info').addClass('openone');
        $(this).parents('.ticket-details-info').find('.ticket-details-info-bottom').toggle(100);

        if($(this).parents('.ticket-details-info').hasClass('opened')) {
            let directionsService = new google.maps.DirectionsService;
            let directionsDisplay = new google.maps.DirectionsRenderer;
            let map = new google.maps.Map($(this).parents('.ticket-details-info').find('.ticket-info-googlemap')[0], {
                zoom: 7,
                center: {lat: 0, lng: 0}
            });
            directionsDisplay.setMap(map);
            let from = $(this).parent().parent().find('.from_city').html(),
                to = $(this).parent().parent().find('.to_city').html();
            calculateAndDisplayRoute(directionsService, directionsDisplay, from, to);
        }
    });
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, start, end) {
    directionsService.route({
        origin: start,
        destination: end,
        travelMode: 'DRIVING',
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } else {
            console.log('Directions request failed due to ' + status);
        }
    });
}
