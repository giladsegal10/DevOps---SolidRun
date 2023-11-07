<script>

jQuery(document).ready(function( $ ) {

  // The features code names and their respective id according to the DOM
  var featuresIDs = {
    CPU: 54, NV0: 99, OS: 100
  };

  // The features code names and their full name in the form
  // we use this dictionary in the floating box
  var featTrueNames = {
    CPU: "CPU", NV0: "Main Storage", OS: "OS"
  };


  // Function to update the features paragraph with selected values
  function updateParagraphWithFeatures() {
    //jQuery('#nf-field-' + floatingBox).trigger('change');
    var featureTexts = [];
    for (var name in featuresIDs) {
      // jQuery('#nf-field-' + featuresIDs[name]).trigger('change');
      var currentValue = jQuery('#nf-field-' + featuresIDs[name] + " option:selected").text();
      // console.log("this is: " + currentValue);
      if (currentValue) {
        featureTexts.push("<b>" + featTrueNames[name] + ": </b>" + currentValue);
      } else {
        featureTexts.push(featTrueNames[name] + ": ");
      }
    }
    jQuery('#floating-feature-div').html(featureTexts.join('<br>'));
  }


  function createFloatingDiv() {
    // Check if the div already exists
    if ($('#floating-feature-div').length === 0) {
        // Create the div and add properties
        var $div = $('<div>', {
            id: 'floating-feature-div',
            css: {
                'position': 'fixed',
                'bottom': '10px',
                'left': '10px',
                'width': '280px',
                'height': '430px',
                'background-color': '#efefef',
                'box-shadow': '0 0 10px rgba(0, 0, 0, 0.3)',
                'padding': '10px',
                'border-radius': '10px',
                'z-index': '1000'
            }
        });

        // Append the div to the body element
        $('body').append($div);
      }
  }

  setTimeout(function() {
    createFloatingDiv();
    updateParagraphWithFeatures();
  }, 250);

});
</script>
