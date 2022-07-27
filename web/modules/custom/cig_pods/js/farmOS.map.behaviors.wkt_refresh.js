(function () {
  farmOS.map.behaviors.wkt_refresh = {
    attach: function (instance) {
      // Get the wkt input element.
      var wkt = document.getElementById("edit-mymap-value");

      // Run a handleInput() callback when input changes.
      wkt.oninput = handleInput;
      function handleInput(e) {
        // If a wkt value is available, remove the old "Geometry"
        // layer, add a new one with the WKT, and zoom to it.
        if (wkt.value === "") {
          instance.map.removeLayer(instance.getLayerByName("Geometry"));
        } else if (wkt.value) {
          instance.map.removeLayer(instance.getLayerByName("Geometry"));
          var layer = instance.addLayer("wkt", {
            title: "Geometry",
            wkt: wkt.value,
          });
          instance.zoomToLayer(layer);
        }
      }
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
})();
