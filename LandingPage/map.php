<script>
  function myMap() {
    var mapProp = {
      center: new google.maps.LatLng(14.5425552, 121.0644146),
      zoom: 18,
    };

    var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

    var marker = new google.maps.Marker({
      position: { lat: 14.5425552, lng: 121.0644146 },
      map: map,
      title: "841 G. de Borja, Pateros, Metro Manila"
    });
  }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap" async defer></script>

<div id="googleMap" style="width: 100%;"></div>