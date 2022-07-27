(function () {
  farmOS.map.behaviors.wkt_refresh = {
    attach: function (instance) {
      // Get the wkt input element.
      var wkt = document.getElementById("edit-mymap-value");
      // Make the geometry label visible and change to "Geometry".
      var label = wkt.parentElement.previousElementSibling;
      label.classList.remove("visually-hidden");
      label.style.fontSize = "16px";
      label.innerHTML =
        "<span 'font-size:16px'>Geometry</span><br><span style='font-size:12px'>You may paste a well known text (WKT) formatted polygon here as an alternative to heads up digitizing on the map</span>";

      // Run a handleInput() callback when input changes.
      wkt.oninput = handleInput;
      function handleInput(e) {
        if (wkt.value) {
          // Clear features from the current edit layer's source.
          instance.edit.layer.getSource().clear();

          // Add a new temporary invisible WKT layer.
          var layer = instance.addLayer("wkt", {
            title: "WKT",
            wkt: wkt.value,
            visible: false,
          });

          // Copy features from the WKT layer to the edit layer.
          instance.edit.layer
            .getSource()
            .addFeatures(layer.getSource().getFeatures());

          // Remove the temporary WKT layer.
          instance.map.removeLayer(layer);

          // Zoom to the edit layer.
          instance.zoomToLayer(instance.edit.layer);
        } else if (wkt.value === "") {
          // Clear features from the current edit layer's source.
          instance.edit.layer.getSource().clear();
        }
      }
    },

    // Make sure this runs after farmOS.map.behaviors.wkt.
    weight: 101,
  };
})();
