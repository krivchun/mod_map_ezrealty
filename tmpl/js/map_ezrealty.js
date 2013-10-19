function mapEzrealtyDisplayObjects(map, markerClusterer, countryData, countriesObjects) {
    var markers = [];
    var markerImage = new google.maps.MarkerImage('http://chart.apis.google.com/chart?chst=d_map_pin_icon_withshadow&chld=home|FFFF00');
    $.each(countriesObjects, function(countryId, countryObjects) {
        for (var i = 0; i < countryObjects.length; i++) {
          var latLng = new google.maps.LatLng(countryObjects[i].lat, countryObjects[i].lon);
          var url = countryObjects[i].url;
          var marker = new google.maps.Marker({
           position: latLng,
           draggable: false,
           icon: markerImage
          });
          mapEzrealtyAddClickEvent(marker, url);
          markers.push(marker);
        }
    });

    markerClusterer.clearMarkers();
    mapEzrealtyShowCountry(map, countryData);
    markerClusterer.addMarkers(markers);
}
function mapEzrealtyAddClickEvent(marker, url) {
    google.maps.event.addListener(marker, 'click', function() {         
        window.open(url);
    });
}
function mapEzrealtyShowCountry(map, countryData) {
    map.setCenter(new google.maps.LatLng(countryData.lat, countryData.lon));
    map.setZoom(parseInt(countryData.zoom));
}
function initMapEzrealty(moduleID, moduleUrl, countriesData, startCountryId, startCountryObjects, clusterSize) {
    // Set up the map
    var countryData = countriesData[startCountryId],
        mapOptions = {
            mapTypeId: google.maps.MapTypeId.HYBRID,
            streetViewControl: true
        },
        styles = [{
            url: moduleUrl + 'tmpl/images/cluster_35.png',
            height: 35,
            width: 35,
            anchor: [12, 0],
            textColor: '#ff00ff',
            textSize: 10
          }, {
            url: moduleUrl + 'tmpl/images/cluster_45.png',
            height: 45,
            width: 45,
            anchor: [18, 0],
            textColor: '#ff0000',
            textSize: 11
          }, {
            url: moduleUrl + 'tmpl/images/cluster_55.png',
            height: 55,
            width: 55,
            anchor: [23, 0],
            textColor: '#ff0000',
            textSize: 12
        }],
        map = new google.maps.Map(document.getElementById(moduleID + '_map_box'), mapOptions),
        markerClusterer = new MarkerClusterer(map, [], {gridSize: clusterSize, styles: styles});

    mapEzrealtyDisplayObjects(map, markerClusterer, countryData, startCountryObjects);

    //Attach events
    $('#' + moduleID + '_coutry_list a').on('click', function() {
        var countryId = $(this).data('id');
        mapEzrealtyShowCountry(map, countriesData[countryId]);
        /*$.get(moduleUrl + 'ajax.php', { country: countryId }, function(data) {
            mapEzrealtyDisplayObjects(map, markerClusterer, countriesData[countryId], data);
        }, 'json');*/

        return false;
    });
}