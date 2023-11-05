<style>
  .R7000-diagram {
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
		CPU: 137, RAM: 138, NV0: 139, OS: 140,
		NIO: 141, SX: 143, M2M1: 144, M2M2: 150,
		M2E: 145, MODEM: 146, PM: 147, DC: 148,
		ENC: 149, EWALL: 151, EFRONT: 152, ETOP: 153,
		EREAR: 154, EBOTTOM: 155, TEMP: 156
	};

	// The features code names and their full name in the form
	// we use this dictionary in the floating box
	var featTrueNames = {
		CPU: "CPU", RAM: "RAM", NV0: "Main Storage", OS: "OS",
		NIO: "NIO", SX: "SX", M2M1: "PCIe x4 slot 1", M2M2: "PCIe x4 slot 2",
		M2E: "M.2 key-E", MODEM: "Modem", PM: "PM", DC: "DCCON", ENC: "Enclosure",
		EWALL: "Walls", EFRONT: "Front Panel", ETOP: "Top Panel",
		EREAR: "Rear Panel", EBOTTOM: "Bottom Panel", TEMP: "Temperature"
	};

  var optionDescriptions = {
    "CPU:R7840U": "15W APU 8C/16T", "CPU:R7840HS": "45W APU 8C/16T", "CPU:R7440U": "15W APU 4C/8T",
    "RAM:NO": "Requires applying thermal paste", "RAM:8": "1x8 GB DDR5", "RAM:16": "2x8 GB DDR5",
    "RAM:32": "2x16 GB DDR5", "RAM:32ECC": "2x16 GB DDR5 ECC", "RAM:64": "2x32 GB DDR5",
    "NV0:1TPLP": "PLP: power loss protection", "SX:4M2": "4x M.2 sockets",
    "M2E:CUST": "M.2 key-E 2230\nPCIe + USB 2\nRequires antennas", "M2E:AX210": "Requires antennas",
    "MODEM:CUST": "M.2 key-E 2230\nUSB 3.2\nRequires antennas & SIM",
    "MODEM:CAT4": "Requires antennas & SIM", "MODEM:CAT12": "Requires antennas & SIM",
    "MODEM:5G": "Requires antennas & SIM", "PM:NO": "12V - 24V DC in", "PM:1260": "12V - 60V DC in",
    "ENC:NO": "requires customer's thermal design",
    "EWALL:TILE": "29x160x130 mm\n15W / 45W CPU\nConduction cooling",
    "EWALL:30W": "45x160x130 mm\n15W CPU\nPassive cooling",
    "EWALL:60W": "73x160x130 mm\n45W CPU\nPassive cooling",
    "ETOP:GENERIC": "No antennas", "ETOP:4ANT": "Required for Wifi or modem",
    "EREAR:2SIM": "Required for modem",
  };



  // for email actions when submitting
  var hiddenFieldsIDs = {
    "email": 195, "full_name": 194,
  };

  var enterHere = 197, 				// id of input field for configuration string
      floatingBox = 199;			// id of floating box

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
		  } else {
				featureTexts.push(featTrueNames[name] + ": ");
			}
		}
		jQuery('#nf-field-' + floatingBox).text(featureTexts.join('\n\n'));
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
    updateParagraphWithFeatures();
    addTitlesToOptions();
    getQueryStringParameter();
    CreateConfigString();
  }, 250);

});
</script>