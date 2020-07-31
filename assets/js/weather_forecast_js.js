if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        jQuery.ajax({
            url: 'weather-ajax-block',
            type: 'POST',
            data: {
                lat: lat,
                lng: lng
            },
            success: function(data) {
                jQuery('.weather-widget').empty();
                jQuery('.weather-widget').append(data);
            },
            cache: false
        });

    });
}