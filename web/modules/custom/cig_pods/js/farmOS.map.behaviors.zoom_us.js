(function () {
  farmOS.map.behaviors.zoom_us = {
    attach: function (instance) {
      // Center/zoom on the United States.
      instance.map.getView().setZoom(4);
      instance.map.getView().setCenter([-11000000, 4400000]);
    },
  };
})();
