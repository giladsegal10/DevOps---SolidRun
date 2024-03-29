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

	var accessoriesDescriptions = {
		"204-3": { // US AC Cable
			title: "AC cable for PSU with North America plug",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#US-%7C-EU-%7C-UK-%7C-AU---AC-Cable"
		},
		"204-0": { // full kit
			title: "The recommended kit for evaluating Bedrock V3000 Basic.\n\nKit includes:\n- PSU 90W (SRBD-PSU90)\n- US AC Cable (SRBD-CABUS)\n- EU AC Cable (SRBD-CABEU)\n- Stand for Bedrock (SRBD-STAND21)\n- Wall mounting bracket (SRBD-WALL21)\n- DIN Rail bracket (SRBD-DIN21)\n- Console mini-USB to USB-A (SRBD-CABCON)\n- SFP+ Module Copper (SRBD-SFP10GBT)\n- Pin for SIM / BIOS reset (SRBD-PIN)",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492994561/Bedrock+V3000+Accessories#Full-evaluation-kit-for-Bedrock-V3000-Basic"
		},
		"204-1": { // minimal kit
			title: "Minimal evaluation kit is a low cost kit for trying out Bedrock.\n\nThe kit includes:\n- Power supply (SRBD-PSU90)\n- Stand (SRBD-STAND21)",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492994561/Bedrock+V3000+Accessories#Minimal-evaluation-kit-for-Bedrock"
		},
		"204-2": { // PSU-90W
			title: "90W power supply.\nRequires AC cable C13 ('Kettle lead')",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#PSU-90W"
		},
		"204-4": { // EU AC Cable
			title: "AC cable for PSU with European plug",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#US-%7C-EU-%7C-UK-%7C-AU---AC-Cable"
		},
		"204-5": { // UK AC Cable
			title: "AC cable for PSU with UK plug",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#US-%7C-EU-%7C-UK-%7C-AU---AC-Cable"
		},
		"204-6": { // AU AC Cable
			title: "AC cable for PSU with Australia plug",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#US-%7C-EU-%7C-UK-%7C-AU---AC-Cable"
		},
		"204-7": { // DC Cable
			title: "Pigtail DC cable for Bedrock.\n\nTo be used with a 3rd party PSU.",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#DC-Cable-Phoenix-connector"
		},
		"204-8": { // Stand
			title: "A desktop stand for Bedrock.\n\nRequired for using Bedrock with no mounting.\n\nFollow link for more details.\n\nIncluded in both evaluation kits.",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/456851457/Bedrock+V3000+Mounting+options#Stand"
		},
		"204-9": { // Wall
			title: "A fixed mounting bracket for attaching Bedrock to a wall.\n\nFollow link for more details.\n\nIncluded in Full evaluation kit",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/456851457/Bedrock+V3000+Mounting+options#Wall-mounting"
		},
		"204-10": { // DIN Rail
			title: "A bracket for mounting Bedrock onto a DIN rail.\n\nThe bracket is specially designed for Bedrock to maximize robustness and convenience.\n\nIncluded in Full Evaluation Kit\n\nFollow link for more details.",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/456851457/Bedrock+V3000+Mounting+options#DIN-Rail-mounting"
		},
		"204-11": { // Remote btn
			title: "A power button on a wire for turning Bedrock on/off when Bedrock is installed in an inaccessible location.",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#Remote-power-button"
		},
		"204-12": { // Harness for btn
			title: "A wire harness for soldering custom power button (optionally with LED) for turning Bedrock on/off when Bedrock is installed in an inaccessible location.",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#Harness-for-custom-remote-power-button"
		},
		"204-13": { // Console
			title: "Console is mini-USB connector with standard USB interface.\nThe mini-USB to USB-A allows connecting to a host PC.\n\nNote: Not needed if you have a mini-USB cable available.\n\nIncluded in Full Evaluation Kit",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492470278/Bedrock+R7000+Accessories#Console-mini-USB-to-USB-A"
		},
		"204-17": { // M.2 display adaptor
			title: "Turns the headless Bedrock V3000 into a development board with a display. Simplifies initial setup and OS installation.",
		},
	};

	var sfp_accessories = {
		"216": {
			title: "SFP Module - direct attached cable",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492994561/Bedrock+V3000+Accessories#SFP%2B-Module-DAC"
		},
		"217": {
			title: "SFP+ module with RJ45\n\n1 unit included in Full Evaluation Kit",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492994561/Bedrock+V3000+Accessories#SFP%2B-Module-Copper"
		},
		"218": {
			title: "SFP Module for Fibre Optics cable",
			link: "https://solidrun.atlassian.net/wiki/spaces/developer/pages/492994561/Bedrock+V3000+Accessories#SFP%2B-Module-Fiber"
		},
	}

	// for email actions when submitting
	var hiddenFieldsIDs = {
		"email": 189, "full_name": 192,
	};

	var enterHere = 188, 				// id of input field for configuration string
			floatingBox = 185;			// id of floating box


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
			setTimeout(function() {
	     	CreateConfigString(); // After change in dropdowns the config string in #enterhere will be updated
	  	}, 10);
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
	    var inputString = jQuery('#nf-field-' + enterHere).val(); // Get the current value of the input field
	    var inputValues = inputString.split(','); // Split by comma to get each key-value pair
	    var inputMap = {};

	    // Create a map from the current input values
	    inputValues.forEach(function(pair) {
	        var parts = pair.split(':');
	        if (parts.length === 2) {
	            inputMap[parts[0]] = parts[1];
	        }
	    });

	    // Iterate over featuresIDs and construct the new string
	    jQuery.each(featuresIDs, function(key, value) {
	        var currentValue = jQuery('#nf-field-' + value).val(); // Get the current selected value of the dropdown
	        if (currentValue) {
	            featureTexts.push(key + ":" + currentValue);
	        } else if (inputMap[key]) {
	            // If there is a user-entered value that doesn't correspond to a valid option, use it
	            featureTexts.push(key + ":" + inputMap[key]);
	        } else {
	            // If there's no valid option selected and no user-entered value, use 'null'
	            featureTexts.push(key + ":");
	        }
	    });

	    // Set the new value for the input field
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

	// Add titles and hyperlinks to Accessories
	function addTitlesToAccessories() {
		jQuery.each(accessoriesDescriptions, function(id, info) {
			// Set title for the checkbox and label
			jQuery('#nf-field-' + id + ', label[for="nf-field-' + id + '"]').attr('title', info.title);

			// If there is a link add click event to the label
			if (info.link) {
				var accessory = jQuery('label[for="nf-field-' + id + '"]');
				var hyperlink = jQuery('<a>', {
					href: info.link,
					text: ' More Info',
					target: '_blank'
				});
				accessory.append(hyperlink);
			}
		});
		// setting title and link to dropdowns
		jQuery.each(sfp_accessories, function(id, info) {
			// set the title for all the options in the dropdown
			jQuery('#nf-field-' + id)
				.attr('title', info.title)
				.css({'width': '30%', 'margin': '0'});

			// adding link below the dropdown
			var hyperlink = jQuery('<a>', {
				href: info.link,
				text: 'More Info',
				target: '_blank',
				css: {'font-weight': 'bold'}
			});
			jQuery('#nf-field-' + id).after(hyperlink);
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
				featureTexts.push("<b>" + featTrueNames[name] + "</b> : " + currentValue);
		  } else {
				featureTexts.push("<b>" + featTrueNames[name] + "</b> : ");
				// disable submit button
				jQuery('#nf-field-125').css({
					'background-color': '#efefef',
	        'color': '#000',
	        'border-color': '#ccc',
					'cursor': 'not-allowed'
				})
			}
		}
		jQuery('#floating-feature-div').html(featureTexts.join('<br>'));
    insertCopyButton();
	}

	// create div for left-down corner floating box
	function createFloatingDiv() {
		// Check if the div already exists
		if ($('#floating-feature-div').length === 0) {
				// Create the div and add properties
				var $div = $('<div>', {
						id: 'floating-feature-div',
						css: {
								'position': 'fixed',
								'bottom': '20px',
								'left': '10px',
								'width': '320px',
								'height': '450px',
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

	// NOT IN USE
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

	// NOT IN USE
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

	// Function to get query string parameters
	function getQueryStringParameter() {
		jQuery.each(hiddenFieldsIDs, function(param, id) {
			hiddenField = param;
			// Escape special regex characters in the parameter name
			hiddenField = hiddenField.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			// Create a regular expression to match the parameter in the query string
			var regex = new RegExp("[\\?&]" + hiddenField + "=([^&#]*)"),
			// Execute the regular expression against the current URL's query string
			results = regex.exec(location.search);
			if (results) {
				// If the parameter is found, decode the URL-encoded parameter value
		 		// The replace(/\+/g, " ") part replaces any plus signs with spaces, which handles cases where spaces are encoded as plus signs
				var value = decodeURIComponent(results[1].replace(/\+/g, " "));
				// Set the email parameter value to the email field
				jQuery('#nf-field-' + id).val(value);
			}
		});
	}

 	// Listen for the custom event at the document level
 	$(document).on(UPDATE_EVENT, updateParagraphWithFeatures);

	$(document).singletonListener('input change', '#nf-field-'+enterHere ,function() {
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

	setTimeout(function() {
			createFloatingDiv();
			updateParagraphWithFeatures();
			insertCopyButton();
			addTitlesToOptions();
			addTitlesToAccessories();
			getQueryStringParameter();
			CreateConfigString();
			// enableOnlyCopyPaste();
	}, 250);

});


/* NOTES
				1. css for different features is inside Ninja Forms plugin:
				Ninja Forms -> styling -> Form styles tab -> Container styles -> Advanced CSS
				Make sure to mark the box 'Show Advanced CSS Properties'
				You can see the css that's in the plugin right here.

				2. css for floating box is inside the V3000 form builder
				You go to the second paragraph field (below 'Enter Configuration Here:')
				In the end of 'Styles' tab there is the Advanced CSS for this element

				3. this code here (includes js and css) is in WP page editor inside a code block.
				Meanwhile the code block is in a passowrd-protected page called 'private-gilad-contact-testing'

				4. Email actions: prior the configurator form, there will be a form (maybe the popup for the bedrock)
				that will require the user to enter an email (meanwhile it's in private page of mine:
				[contact-form-7 id="82283" title="Bedrock form email testing"]). The email that is sent
				is with a link to the configurator page. the link itself is with the email paramater.
				The NF page opens with this parameter on the link and it catches this pararm and use
				it to populate the email field. The email field in the NF form exists but is with `display:none;`

				[contact-form-7 id="82283" title="Bedrock form email testing"] - https://www.solid-run.com/gilad-bedrock-configurator/

*/
</script>




<style>
	.label-left .nf-field-label {
		padding: 0 0 20px 0;
		text-align: left;
		font-size:20px
	}

	.nf-form-content .nf-field-container {
		margin-bottom: 0px;
	}

	/* "i" icon next to each feature name */
	.nf-help {
		padding-top:4px;
	}

	/*
	132 - hiding the html block with the configuration (and the button inside it) (V3000)
	189 - hiding the email field (that is prepopulated from link in email) (V3000)
	192 - hiding full name field (V3000)
	136 - hiding the html block with the configuration (R7000)
	195 - hiding the email field (that is prepopulated from link in email) (R7000)
	194 - hiding full name field (R7000)
	*/
	#nf-field-132-wrap .nf-field-element,
	#nf-field-189-container,
	#nf-error-189 .nf-error-msg,
	#nf-field-192-container,
	#nf-field-136-wrap .nf-field-element,
	#nf-field-195-container,
	#nf-field-194-container
	{
		display: none;
	}

	.ninja-forms-req-symbol {
		color: #e80000!important;
	}

	.nf-error-msg {
		margin-left: -10px;
		margin-top: -18px;
		margin-bottom: 15px;
	}

</style>


<div class="nf-field-element">
	<input type="text" value="" class="ninja-forms-field nf-element" id="nf-field-201" name="nf-field-201" aria-invalid="false" aria-describedby="nf-error-201" aria-labelledby="nf-label-field-201">
</div>
