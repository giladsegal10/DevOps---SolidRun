<style>
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

	var enterHere = 188; 		// id of input field for configuration string

	// Custom event that will be triggered after updating a field
  var UPDATE_EVENT = 'updateFeatures';

	// Function to update the paragraph and copy button when a dropdown changes
	function handleDropdownChange() {
    // Wait for a short time before updating, to ensure all changes are completed
    setTimeout(function() {
      updateParagraphWithFeatures();
    }, 100); // 100 ms delay
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
		}
	});

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
		var featureTexts = [];
		for (var name in featuresIDs) {
	          var currentValue = jQuery('#nf-field-' + featuresIDs[name] + " option:selected").text();
		  if (currentValue) {
				featureTexts.push("<b>" + featTrueNames[name] + "</b> : " + currentValue);
		  } else {
				featureTexts.push("<b>" + featTrueNames[name] + "</b> : ");
        // disable the subit button because a certain feature was not selected 
				jQuery('#nf-field-125').css({
					'background-color': '#efefef',
	        'color': '#000',
	        'border-color': '#ccc',
          'cursor': 'not-allowed'
				})
			}
		}
		jQuery('#floating-feature-div').html(featureTexts.join('<br>'));
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

	// Function to get query string parameters for email and full name (hidden fields in the form)
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
    addTitlesToOptions();
    addTitlesToAccessories();
    getQueryStringParameter();
    CreateConfigString();
  }, 250);
});

</script>
