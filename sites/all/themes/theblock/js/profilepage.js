function goGaugeHorsepower(value) {
	var horsepower = new jGauge(); // Create a new jGauge.
	horsepower.id = 'gauge_horsepower'; // Link the new jGauge to the placeholder DIV.
	horsepower.width = 120;
	horsepower.height = 120;
	horsepower.segmentStart = -220;
	horsepower.segmentEnd = 40;
	horsepower.imagePath = '/sites/all/themes/theblock/images/gauge.svg';
	horsepower.needle.imagePath = '/sites/all/themes/theblock/images/gauge-needle.svg';
	horsepower.needle.yOffset = 0;
	horsepower.label.color = '#e3dfc5';
	horsepower.ticks.labelColor = '#e3dfc5';
	horsepower.ticks.count = 11;
	horsepower.ticks.end = 900;
	horsepower.ticks.labelPrecision = 0;
	horsepower.ticks.thickness = 0;
	horsepower.ticks.labelRadius = 36;
	horsepower.label.prefix = 'HP';
	horsepower.label.yOffset = 40;
	horsepower.range.thickness = 0;
	
	// This function is called by jQuery once the page has finished loading.
	horsepower.init(); // Put the jGauge on the page by initialising it.
	horsepower.setValue(value);
}

function goGaugeTorque(value) {
	var torque = new jGauge(); // Create a new jGauge.
	torque.id = 'gauge_torque'; // Link the new jGauge to the placeholder DIV.
	torque.width = 120;
	torque.height = 120;
	torque.segmentStart = -220;
	torque.segmentEnd = 40;
	torque.imagePath = '/sites/all/themes/theblock/images/gauge.svg';
	torque.needle.imagePath = '/sites/all/themes/theblock/images/gauge-needle.svg';
	torque.needle.yOffset = 0;
	torque.label.color = '#e3dfc5';
	torque.ticks.labelColor = '#e3dfc5';
	torque.ticks.count = 11;
	torque.ticks.end = 900;
	torque.ticks.labelPrecision = 0;
	torque.ticks.thickness = 0;
	torque.ticks.labelRadius = 36;
	torque.label.prefix = 'LB-FT';
	torque.label.yOffset = 40;
	torque.range.thickness = 0;
	
	// This function is called by jQuery once the page has finished loading.
	torque.init(); // Put the jGauge on the page by initialising it.
	torque.setValue(value);
}

function goGaugeZeroSixty(value) {
	if (jQuery('#gauge_zerosixty').length != 0) {
		var zerosixty = new jGauge(); // Create a new jGauge.
		zerosixty.id = 'gauge_zerosixty'; // Link the new jGauge to the placeholder DIV.
		zerosixty.width = 120;
		zerosixty.height = 120;
		zerosixty.segmentStart = -220;
		zerosixty.segmentEnd = 40;
		zerosixty.imagePath = '/sites/all/themes/theblock/images/gauge.svg';
		zerosixty.needle.imagePath = '/sites/all/themes/theblock/images/gauge-needle.svg';
		zerosixty.needle.yOffset = 0;
		zerosixty.label.color = '#e3dfc5';
		zerosixty.ticks.labelColor = '#e3dfc5';
		zerosixty.ticks.count = 11;
		zerosixty.ticks.end = 60;
		zerosixty.ticks.labelPrecision = 0;
		zerosixty.ticks.thickness = 0;
		zerosixty.ticks.labelRadius = 36;
		zerosixty.label.prefix = 'SEC';
		zerosixty.label.yOffset = 40;
		zerosixty.range.thickness = 0;
		
		// This function is called by jQuery once the page has finished loading.
		zerosixty.init(); // Put the jGauge on the page by initialising it.
		zerosixty.setValue(value);
	}
}

function goGaugeQuarterMile(value) {
	if (jQuery('#gauge_quartermile').length != 0) {
		var quartermile = new jGauge(); // Create a new jGauge.
		quartermile.id = 'gauge_quartermile'; // Link the new jGauge to the placeholder DIV.
		quartermile.width = 120;
		quartermile.height = 120;
		quartermile.segmentStart = -220;
		quartermile.segmentEnd = 40;
		quartermile.imagePath = '/sites/all/themes/theblock/images/gauge.svg';
		quartermile.needle.imagePath = '/sites/all/themes/theblock/images/gauge-needle.svg';
		quartermile.needle.yOffset = 0;
		quartermile.label.color = '#e3dfc5';
		quartermile.ticks.labelColor = '#e3dfc5';
		quartermile.ticks.count = 11;
		quartermile.ticks.end = 30;
		quartermile.ticks.labelPrecision = 0;
		quartermile.ticks.thickness = 0;
		quartermile.ticks.labelRadius = 36;
		quartermile.label.prefix = 'SEC';
		quartermile.label.yOffset = 40;
		quartermile.range.thickness = 0;
		
		// This function is called by jQuery once the page has finished loading.
		quartermile.init(); // Put the jGauge on the page by initialising it.
		quartermile.setValue(value);
	}
}