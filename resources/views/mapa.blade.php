<!DOCTYPE html>
<html>
<head>
    <title>Mapa</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>

    <h2>Mi Mapa</h2>

    <div id="map"></div>

    <script>
        function initMap() {
            const ubicacion = { lat: 16.90780, lng: -92.09502 }; 

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: ubicacion,
            });

            new google.maps.Marker({
                position: ubicacion,
                map: map,
            });
        }
    </script>

    <script async
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap">
    </script>

</body>
</html>
