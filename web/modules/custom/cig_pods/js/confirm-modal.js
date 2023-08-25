(function ($, Drupal) {
    'use strict';
  
    Drupal.behaviors.confirmModal = {

      attach: function() {

        $('[data-drupal-selector="edit-cancel"], [data-drupal-selector="edit-actions-cancel"]').click(function(event){
            event.preventDefault();

            var ajaxSettings = {
            url: '/modals/confirm-modal'
            };
            var myAjaxObject = Drupal.ajax(ajaxSettings);
            myAjaxObject.execute();

        });

        $('.popup-close-button').click(function(event){
            event.preventDefault();
            $('.ui-icon-closethick').click();
        });

      }
    };
  
  })(jQuery, Drupal);