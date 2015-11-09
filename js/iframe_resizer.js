(function ($) {
  Drupal.behaviors.iframe_resizer = {
    attach: function (context, settings) {
      $('iframe', context).iFrameResize( {heightCalculationMethod:'lowestElement'} );
    }
  };
}(jQuery));
