(function () {
  farmOS.map.behaviors.latlon_refresh = {
    attach: function (instance) {

      // Get the lat/lon input elements and "Add point" button.
      var lat = document.getElementById('edit-lat');
      var lon = document.getElementById('edit-lon');
      var button = document.getElementById('edit-add-point')

      // Run a addPoint() callback when button is clicked.
      button.onclick = handleInput;
      function handleInput(e) {

        // Prevent the button from submitting the form.
        e.preventDefault();

        // If both lat and lon are not available, stop here.
        if (!(lat.value && lon.value)) {
          return;
        }

        // Add a temporary layer with the lat/lon point.
        // We do this because we don't have access to the OpenLayers Point
        // class in this context, so we use a workaround to generate one.
        var layer = instance.addLayer('wkt', {
          title: 'WKT',
          wkt: 'POINT(' + lon.value + ' ' + lat.value + ')',
          visible: false,
        });

        // Pull the features out of the temporary layer and add them to the
        // drawing layer.
        var features = layer.getSource().getFeatures();
        if (features) {
          instance.edit.layer.getSource().addFeatures(features)
        }

        // Refresh WKT below the map.
        instance.map.getTargetElement().parentElement.querySelector('[data-map-geometry-field]').value = instance.edit.getWKT();

        // Remove the temporary WKT layer.
        instance.map.removeLayer(layer);

        // Zoom to fit all available geometries.
        instance.zoomToVectors();
      }
    },

    // Make sure this runs after farmOS.map.behaviors.input.
    weight: 102
  };
}());
