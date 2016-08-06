<?PHP

// PHP parser for ical. No support for timezones!

// Function to split lines
function split_ics_lines ($input) {
	$output = array();
	$lines = explode(PHP_EOL, $input.trim(PHP_EOL));
	foreach ($lines as $line) {
		if (strpos($line, ":") == False) continue;
		$key = substr($line, 0, strpos($line, ':'));
		$value = str_replace($key.":", "", $line);

		if (strpos($key, ";") > 1) $key = substr($key, 0, strpos($key, ";"));
		$output[$key] = $value;
	}
	return ($output);
}

// Parser for ics
function parse_ics ($str) {

	if (substr($str, 0, 15) != "BEGIN:VCALENDAR") die ("This is not a calendar. Supplied string:".$str);
	else if (substr($str, strlen($str) - 13, 13) != "END:VCALENDAR") die ("This is not a calendar. Supplied string:".$str."<br /><br />No end tag found.");

	// Create calendar array that will be returned
	$calendar = array("specification" => array(), "events" => array());

	// Get specification information on the whole calendar
	$introduction_str = substr ($str, 15, strpos($str, "BEGIN", 15) - 15);
	$calendar["specification"] = split_ics_lines($introduction_str);
	
	// Create variables for positions
	$position1 = 0;
	$position2 = 1;

	// Get events
	for ($i = 0; $i < substr_count($str, "BEGIN:VEVENT"); $i++) {

		$position1 = strpos($str, "BEGIN:VEVENT", $position2) + 12;
		$position2 = strpos($str, "END:VEVENT", $position1);

		$event_str = substr($str, $position1, $position2 - $position1);
		$calendar["events"][] = split_ics_lines($event_str);
	}
	
	return($calendar);
}

?>
