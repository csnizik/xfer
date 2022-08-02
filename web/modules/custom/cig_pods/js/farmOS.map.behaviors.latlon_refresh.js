(function () {
  farmOS.map.behaviors.latlon_refresh = {
    attach: function (instance) {

      // Get the lat/lon input elements
      var lat = document.getElementById('edit-lat');
      var lon = document.getElementById('edit-lon');

      // Run a handleInput() callback when input changes.
      lat.oninput = handleInput;
      lon.oninput = handleInput;
      function handleInput(e) {

        // If both lat and lon are available, remove the old "Geometry"
        // layer, add a new one with the lat/lon point, and zoom to it.
        if (lat.value && lon.value) {
          instance.map.removeLayer(instance.getLayerByName('Geometry'))
          var layer = instance.addLayer('wkt', {
            title: 'Geometry',
            wkt: 'POINT(' + lon.value + ' ' + lat.value + ')'
          });
          instance.zoomToLayer(layer);
        }
      }
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101
  };
}());
