/* You can add styles here if needed for the entire form */
		#frm_field_66_container > .frm_form_field {
    	padding-bottom: 7px;  /* Adjust this value as per your requirement */
			padding-top: 1px;
		}

		#frm_field_68_container > .frm_form_field {
    	padding-bottom: 1px;  /* Adjust this value as per your requirement */
		}

    <input size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required wpcf7-not-valid" aria-required="true" aria-invalid="true" value="" type="text" name="your-company" aria-describedby="wpcf7-f78971-o4-ve-your-company">

<script>
var fieldID = 111; // RAM field
var newValue = '8';

jQuery( '#nf-field-' + fieldID ).val( newValue ).trigger( 'change' );


if (!window.flag) {
	var fieldID = 111; // RAM field
	var newValue = '8';

	jQuery( '#nf-field-' + fieldID ).val( newValue ).trigger( 'change' );
	// set the flag to prevent re-exc
	window.flag = true;
}


jQuery(document).ready(function($) {
  $('#nf-field-' + 158).on('input change' function() {
    var currentValue = $(this).val();

    console.log("Current value: ", currentValue);

    // ... perform your desired operations here ...
  });
});

// ##################################################
  (function($) {
	$.fn.singletonListener = function(eventType, callback) {
  		// Remove any existing listeners of the same type
  		this.off(eventType);

  		// Attach the new listener
  		this.on(eventType, callback);

  		return this;  // To maintain chainability
  	};
  })(jQuery);

  var featuresIDs = {
  	CPU: 110, RAM: 111, NV0: 112, OS: 113,
  	NIO: 114, SX: 115, NV1: 116, NV2: 126,
  	WIFI: 117, MODEM: 118, PM: 119, DC: 120,
  	ENC: 121, EWALL: 122, EFRONT: 123, ETOP: 124,
		EREAR: 127, EBOTTOM: 128, TEMP: 129
  };

	var featTrueNames = {
		CPU: "CPU", RAM: "RAM", NV0: "Main Storage", OS: "OS",
  	NIO: "NIO", SX: "SX", NV1: "SX Storage 1", NV2: "SX Storage 2",
  	WIFI: "WiFi", MODEM: "Modem", PM: "PM", DC: "DCCON", ENC: "Enclosure",
		EWALL: "Walls", EFRONT: "Front Panel", ETOP: "Top Panel",
		EREAR: "Rear Panel", EBOTTOM: "Bottom Panel", TEMP: "Temperature"
	};

  var enterHere = 158;
	var floatingBox = 185;

	function updateParagraphWithFeatures() {
    var featureTexts = [];
    for (var name in featuresIDs) {
        var currentValue = jQuery('#nf-field-' + featuresIDs[name]).val();
        if (currentValue) {
            featureTexts.push(featTrueNames[name] + "<br>" + currentValue);
        }
    }
    jQuery('#nf-field-' + floatingBox).html(featureTexts.join('<br><br>'));
	}

  jQuery(document).ready(function($) {
  	jQuery('#nf-field-' + enterHere).singletonListener('change', function() {
  		var currentValue = $(this).val();
  		var featurePairs = currentValue.split(',');
  		var parsedFeatures = {};

  		featurePairs.forEach(function(pair) {
  			var parts = pair.split(':');
  			parsedFeatures[parts[0]] = parts[1]; // CPU => V3C48
  		});
  		//console.log(parsedFeatures);

  		for (var featureName in parsedFeatures) {
            // Check if the featureName exists in featuresIDs
            if (featuresIDs.hasOwnProperty(featureName)) {
                // Set the value in the corresponding dropdown field
                jQuery('#nf-field-' + featuresIDs[featureName])
                    .val(parsedFeatures[featureName])
                    .trigger('change');
            }
        }
  	});
  	jQuery('#nf-field-' + enterHere).trigger('change'); // to manually trigger the input field
		for (var id in featuresIDs) {
			jQuery('#nf-field-' + featuresIDs[id]).trigger('change');
			jQuery('#nf-field-' + featuresIDs[id]).on('change', updateParagraphWithFeatures);
		}
  });

// ##################################################


(function($) {
$.fn.singletonListener = function(eventType, callback) {
		// Remove any existing listeners of the same type
		this.off(eventType);

		// Attach the new listener
		this.on(eventType, callback);

		return this;  // To maintain chainability
	};
})(jQuery);

var featuresIDs = {
	CPU: 110, RAM: 111, NV0: 112, OS: 113,
	NIO: 114, SX: 115, NV1: 116, NV2: 126,
	WIFI: 117, MODEM: 118, PM: 119, DC: 120,
	ENC: 121, EWALL: 122, EFRONT: 123, ETOP: 124,
	EREAR: 127, EBOTTOM: 128, TEMP: 129
};

var featTrueNames = {
	CPU: "CPU", RAM: "RAM", NV0: "Main Storage", OS: "OS",
	NIO: "NIO", SX: "SX", NV1: "SX Storage 1", NV2: "SX Storage 2",
	WIFI: "WiFi", MODEM: "Modem", PM: "PM", DC: "DCCON", ENC: "Enclosure",
	EWALL: "Walls", EFRONT: "Front Panel", ETOP: "Top Panel",
	EREAR: "Rear Panel", EBOTTOM: "Bottom Panel", TEMP: "Temperature"
};

var enterHere = 158;
var floatingBox = 185;

function updateParagraphWithFeatures() {
	var featureTexts = [];
	for (var name in featuresIDs) {
			var currentValue = jQuery('#nf-field-' + featuresIDs[name]).val();
			if (currentValue) {
					featureTexts.push(featTrueNames[name] + "\n" + currentValue);
			}
	}
	jQuery('#nf-field-' + floatingBox).text(featureTexts.join('\n\n'));
}

jQuery(document).ready(function($) {
	jQuery('#nf-field-' + enterHere).singletonListener('change', function() {
		var currentValue = $(this).val();
		var featurePairs = currentValue.split(',');
		var parsedFeatures = {};

		featurePairs.forEach(function(pair) {
			var parts = pair.split(':');
			parsedFeatures[parts[0]] = parts[1]; // CPU => V3C48
		});
		//console.log(parsedFeatures);

		for (var featureName in parsedFeatures) {
					// Check if the featureName exists in featuresIDs
					if (featuresIDs.hasOwnProperty(featureName)) {
							// Set the value in the corresponding dropdown field
							jQuery('#nf-field-' + featuresIDs[featureName])
									.val(parsedFeatures[featureName])
									.trigger('change');
					}
			}
	});
	jQuery('#nf-field-' + enterHere).trigger('change'); // to manually trigger the input field
	jQuery('#nf-field-' + floatingBox).trigger('change');
	for (var id in featuresIDs) {
		jQuery('#nf-field-' + featuresIDs[id]).trigger('change');
		jQuery('#nf-field-' + featuresIDs[id])
				.on('change', updateParagraphWithFeatures);
}
});

// ############################################################## 11.10

jQuery(document).ready(function( $ ) {
	$.fn.singletonListener = function(eventType, selector, callback) {
        // Attach the event listener to a parent element
        this.on(eventType, selector, function() {
          // Inside the event handler, `this` refers to the matched element
          callback.call(this);
        });

        return this; // To maintain chainability
  };

	var featuresIDs = {
		CPU: 110, RAM: 111, NV0: 112, OS: 113,
		NIO: 114, SX: 115, NV1: 116, NV2: 126,
		WIFI: 117, MODEM: 118, PM: 119, DC: 120,
		ENC: 121, EWALL: 122, EFRONT: 123, ETOP: 124,
		EREAR: 127, EBOTTOM: 128, TEMP: 129
	};

	var featTrueNames = {
		CPU: "CPU", RAM: "RAM", NV0: "Main Storage", OS: "OS",
		NIO: "NIO", SX: "SX", NV1: "SX Storage 1", NV2: "SX Storage 2",
		WIFI: "WiFi", MODEM: "Modem", PM: "PM", DC: "DCCON", ENC: "Enclosure",
		EWALL: "Walls", EFRONT: "Front Panel", ETOP: "Top Panel",
		EREAR: "Rear Panel", EBOTTOM: "Bottom Panel", TEMP: "Temperature"
	};

	var enterHere = 158;
	var floatingBox = 185;

	// Custom event that will be triggered after updating a field
  var UPDATE_EVENT = 'updateFeatures';

	function updateParagraphWithFeatures() {
  	//console.log("Hello Function");
    //jQuery('#nf-field-' + floatingBox).trigger('change');
		var featureTexts = [];
		for (var name in featuresIDs) {
      //jQuery('#nf-field-' + featuresIDs[name]).trigger('change');
			var currentValue = jQuery('#nf-field-' + featuresIDs[name] + " option:selected").text();
      //console.log("this is: " + currentValue);
			if (currentValue) {
				featureTexts.push(featTrueNames[name] + "\n" + currentValue);
			}
		}
		jQuery('#nf-field-' + floatingBox).text(featureTexts.join('\n\n'));
	}

  // Function to handle the click event of the copy button
  function copyConfigurationString() {
    var textToCopy = jQuery('#nf-field-132-wrap .nf-field-element p:nth-child(5)').text();
    var tempTextarea = jQuery('<textarea>');
    jQuery('body').append(tempTextarea);
    tempTextarea.val(textToCopy).select();
    document.execCommand('copy');
    tempTextarea.remove();
    console.log("Copied: " + textToCopy); // Log the copied text
  }

  // Listen for the custom event at the document level
  $(document).on(UPDATE_EVENT, updateParagraphWithFeatures);

	$(document).singletonListener('change', '#nf-field-'+enterHere,function() {
		var currentValue = $(this).val();
		var featurePairs = currentValue.split(',');
		var parsedFeatures = {};

		featurePairs.forEach(function(pair) {
			var parts = pair.split(':');
			parsedFeatures[parts[0]] = parts[1]; // CPU => V3C48
		});
		//console.log(parsedFeatures);

		for (var featureName in parsedFeatures) {
			// Check if the featureName exists in featuresIDs
			if (featuresIDs.hasOwnProperty(featureName)) {
				// Set the value in the corresponding dropdown field
				jQuery('#nf-field-' + featuresIDs[featureName])
				.val(parsedFeatures[featureName])
				.trigger('change')
				.trigger(UPDATE_EVENT);
			}
		}
	});
	jQuery('#nf-field-' + enterHere).trigger('change'); // to manually trigger the input field

	for (var id in featuresIDs) {
		jQuery('#nf-field-' + featuresIDs[id]).on('change', function() {
      $(document).trigger(UPDATE_EVENT);  // Triggering the custom event
  	});
	}

	jQuery('#copyButton').click(copyConfigurationString);

  updateParagraphWithFeatures();
  setTimeout(updateParagraphWithFeatures, 250);
});


// ##############################################################


// ############# code with button javascript #############

// <a id="copyButton" class="button">Copy String</a>

<style>
.button {
  height: 25px;
  width: 75px;
  text-align: center;
  border: 2px solid rgba(33, 68, 72, 0.59);
}
.V3000-diagram {
  font-size: 18px;
  font-weight: bold;
}
</style>

<script>

jQuery(document).ready(function( $ ) {
	$.fn.singletonListener = function(eventType, selector, callback) {
    // Attach the event listener to a parent element
    this.on(eventType, selector, function() {
      // Inside the event handler, `this` refers to the matched element
      callback.call(this);
    });

    return this; // To maintain chainability
  };

	// All the options codes for validation of configuration string
	var optionCodes = [
		"CPU:V3C48", "CPU:V3C18I", "RAM:8", "RAM:16", "RAM:32", "RAM:32ECC", "RAM:64", "NV0:NO",
		"NV0:1TPLP", "NV0:1T", "NV0:256GEN3", "NV0:512GEN3", "NV0:1TGEN3", "NV0:2TGEN3", "OS:NO",
		"OS:UBU", "OS:WIN11P", "OS:NA", "NIO:V3B", "NIO:V3MIN",	"SX:NO", "SX:4M2", "NV1:NO", "NV1:1T",
		"NV1:1TGEN3", "NV1:2TGEN3", "NV1:NA",	"NV2:NO", "NV2:1T", "NV2:1TGEN3", "NV2:2TGEN3",
		"NV2:NA", "WIFI:NO", "WIFI:CUST", "WIFI:AX210", "WIFI:NA", "MODEM:NO", "MODEM:CUST",
		"MODEM:CAT4", "MODEM:CAT12", "MODEM:5G", "MODEM:NA", "PM:NO", "PM:1260", "DC:TERM", "ENC:YES",
		"ENC:NO", "EWALL:TILE", "EWALL:60W", "EWALL:30W", "EWALL:NA", "EFRONT:V3BASIC", "EFRONT:NA",
		"ETOP:4ANT", "ETOP:GENERIC", "ETOP:4XSMA", "ETOP:NA", "EREAR:2SIM", "EREAR:GENERIC",
		"EREAR:NA", "EBOTTOM:GENERIC", "EBOTTOM:NA", "TEMP:070", "TEMP:4085"
	];

	// The features code names and their respective id according to the DOM
	var featuresIDs = {
		CPU: 110, RAM: 111, NV0: 112, OS: 113,
		NIO: 114, SX: 115, NV1: 116, NV2: 126,
		WIFI: 117, MODEM: 118, PM: 119, DC: 120,
		ENC: 121, EWALL: 122, EFRONT: 123, ETOP: 124,
		EREAR: 127, EBOTTOM: 128, TEMP: 129
	};

	// The features code names and their full name in the form
	// we use this dictionary in the floating box
	var featTrueNames = {
		CPU: "CPU", RAM: "RAM", NV0: "Main Storage", OS: "OS",
		NIO: "NIO", SX: "SX", NV1: "SX Storage 1", NV2: "SX Storage 2",
		WIFI: "WiFi", MODEM: "Modem", PM: "PM", DC: "DCCON", ENC: "Enclosure",
		EWALL: "Walls", EFRONT: "Front Panel", ETOP: "Top Panel",
		EREAR: "Rear Panel", EBOTTOM: "Bottom Panel", TEMP: "Temperature"
	};

	var optionDescriptions = {
		"CPU:V3C48": "45W CPU 8C/16T", "CPU:V3C18I": "15W CPU 8C/16T",
		"RAM:NO": "Requires applying thermal paste", "RAM:8": "1x8 GB DDR5",
		"RAM:16": "2x8 GB DDR5", "RAM:32": "2x16 GB DDR5",
		"RAM:32ECC": "2x16 GB DDR5 ECC", "RAM:64": "2x32 GB DDR5",
		"NV0:1TPLP": "PLP: power loss protection",
		"SX:4M2": "4x M.2 sockets",
		"WIFI:CUST": "M.2 key-E 2230\nPCIe + USB 2\nRequires antennas",
		"WIFI:AX210": "Requires antennas",
		"MODEM:CUST": "M.2 key-E 2230\nUSB 3.2\nRequires antennas & SIM",
		"MODEM:CAT4": "Requires antennas & SIM", "MODEM:CAT12": "Requires antennas & SIM",
		"MODEM:5G": "Requires antennas & SIM",
		"PM:NO": "12V - 24V DC in", "PM:1260": "12V - 60V DC in",
		"ENC:NO": "requires customer's thermal design",
		"EWALL:TILE": "29x160x130 mm\n15W / 45W CPU\nConduction cooling",
		"EWALL:30W": "45x160x130 mm\n15W CPU\nPassive cooling",
		"EWALL:60W": "73x160x130 mm\n45W CPU\nPassive cooling",
		"ETOP:GENERIC": "No antennas", "ETOP:4ANT": "Required for Wifi or modem",
		"EREAR:2SIM": "Required for modem",
	};

	var enterHere = 188; 		// id of input field for configuration string
	var floatingBox = 185;	// id of floating box

	// Custom event that will be triggered after updating a field
  var UPDATE_EVENT = 'updateFeatures';

	// Function to update the paragraph and copy button when a dropdown changes
	function handleDropdownChange() {
   	// Wait for a short time before updating, to ensure all changes are completed
   	setTimeout(function() {
     	updateParagraphWithFeatures();
  	}, 100); // 100 ms delay
		insertCopyButton(); // Re-insert the copy button but no timeout necessary
	}

	// Event delegation for dropdown change events
	$('body').on('change', 'select', function(event) {
		// Check if the changed select element is one of your feature dropdowns
		if (Object.values(featuresIDs).includes(parseInt($(event.target).attr('id').replace('nf-field-', '')))) {
			handleDropdownChange();
			CreateConfigString(); // After change in dropdowns the config string in #enterhere will be updated
			addTitlesToOptions();
			// enableOnlyCopyPaste();
		}
	});

	// function enableOnlyCopyPaste() {
	// 	var textareaElement = $('#nf-field-' + enterHere);
	// 	textareaElement.attr('readonly', "readonly");
	//
	// 	textareaElement.on('paste', function() {
	//     textareaElement.prop('readonly', false);  // Temporarily remove readonly for pasting
	//     setTimeout(function() {
	//       textareaElement.prop('readonly', true);  // Restore readonly after paste is done
	//     }, 50);
  // 	});
	// }

	// Create config string from dropdowns
	function CreateConfigString() {
		var featureTexts = [];
		jQuery.each(featuresIDs, function(key, value) {
    	//console.log(key + ":" + value);
			var currentValue = jQuery('#nf-field-' + value).val();
			if (currentValue) {
				featureTexts.push(key + ":" + currentValue);
		  }
		});
		jQuery('#nf-field-' + enterHere).val(featureTexts.join(','));
	}

	/*
	Function to add titles to options. The titles are notes from the V3000 diagram.
	The function goes over every feature (using DOM id's), collects all the options
	from the feature and then for each option it checks if there is a key for it in
	optionDescriptions map.
	*/
	function addTitlesToOptions() {
		jQuery.each(featuresIDs, function(feature, id) {
			// Find the select element and its options
			var selectElement = $('#nf-field-' + id);
			var options = selectElement.find('option');

			// Add a title to each option
			options.each(function() {
				var optionValue = $(this).attr('value');
				var fullKey = feature + ":" + optionValue;
				var description = optionDescriptions[fullKey];
				if (description) {
					$(this).attr('title', description);
				}
			});
		});
	}

  // Function to update the features paragraph with selected values
	function updateParagraphWithFeatures() {
  	//jQuery('#nf-field-' + floatingBox).trigger('change');
		var featureTexts = [];
		for (var name in featuresIDs) {
    	// jQuery('#nf-field-' + featuresIDs[name]).trigger('change');
	    var currentValue = jQuery('#nf-field-' + featuresIDs[name] + " option:selected").text();
      // console.log("this is: " + currentValue);
		  if (currentValue) {
				featureTexts.push(featTrueNames[name] + ": " + currentValue);
		  }
		}
		jQuery('#nf-field-' + floatingBox).text(featureTexts.join('\n\n'));
    insertCopyButton();
	}

	 // Function to handle the click event of the copy button
	function copyConfigurationString() {
    var textToCopy = jQuery('#nf-field-132-wrap .nf-field-element p:nth-child(4)').text();
    var tempTextarea = jQuery('<textarea>');
    jQuery('body').append(tempTextarea);
    tempTextarea.val(textToCopy).select();
    document.execCommand('copy');
    tempTextarea.remove();
    //console.log("Copied: " + textToCopy); // Log the copied text
	}

 	// Function to insert the 'Copy String' button if it doesn't already exist
 	function insertCopyButton() {
    // Check if the button already exists in the DOM
    if (!$("#copyButton").length) {
      var $button = $('<a id="copyButton" class="button">Copy String</a>');
      $('#nf-field-132-wrap .nf-field-element p:nth-child(4)').after($button);
      $('#copyButton').click(copyConfigurationString);
      console.log("Button inserted");
    } else {
      console.log("Button already exists");
    }
 	}

 	// Listen for the custom event at the document level
 	$(document).on(UPDATE_EVENT, updateParagraphWithFeatures);

	$(document).singletonListener('input change', '#nf-field-'+enterHere ,function() {
		var currentValue = $(this).val();
		var featurePairs = currentValue.split(','); //CPU:V3C48
		// var parsedFeatures = {};

		featurePairs.forEach(function(pair) {
			var parts = pair.split(':'); // 0 => CPU ; 1 => V3C48
			// parsedFeatures[parts[0]] = parts[1]; // CPU => V3C48
			// if key is a valid feature name and the optionCode exist continue
			if (featuresIDs.hasOwnProperty(parts[0]) && jQuery.inArray(pair, optionCodes)) {	// CPU, RAM ...
				// Set the value in the corresponding dropdown field
				jQuery('#nf-field-' + featuresIDs[parts[0]])
				.val(parts[1])
				.trigger('change')
				.trigger(UPDATE_EVENT);
			}
		});
	});
	jQuery('#nf-field-' + enterHere).trigger('change'); // to manually trigger the input field

	setTimeout(function() {
			updateParagraphWithFeatures();
			insertCopyButton();
			addTitlesToOptions();
			// enableOnlyCopyPaste();
	}, 250);

});


/* NOTES
				1. css for different features is inside Ninja Forms plugin:
				Ninja Forms -> styling -> Form styles tab -> Container styles -> Advanced CSS
				make sure to mark the box 'Show Advanced CSS Properties'

				2. css for floating box is inside the V3000 form builder
				You go to the second paragraph field (below 'Enter Configuration Here:')
				In the end of 'Styles' tab there is the Advanced CSS for this element

				3. this code here (includes js and css) is in WP page editor inside a code block
*/
</script>
