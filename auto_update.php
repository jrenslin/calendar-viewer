<?PHP

// Function to update .ics files if they are older than 1 day
function get_calendars () {
    $max_age = 60 * 60 * 24;
    if (!file_exists('calendars/urls')) return;
    $url_file = file_get_contents('calendars/urls');
    $url_list = split_url_lines($url_file);

    foreach ($url_list as $name => $url) {
        $file_path = 'calendars/'.$name.'.ics';
        if (!file_exists($file_path) || file_age($file_path) > $max_age) {
            if(file_put_contents($file_path, file_get_contents($url))) continue;
            else {
                syslog(LOG_WARNING, 'Failed to download calendar '.$name.' from '.$url);
            }
        }
    }
}

// Function to check file age
// @param $file - file name
// @return - age in seconds
function file_age ($file) {
    $file_time = filemtime($file);
    $now = time();
    $file_age = $now - $file_time;
    return $file_age;
}

// Function to split lines
function split_url_lines ($input) {
	$output = array();
	$lines = explode(PHP_EOL, $input.trim(PHP_EOL));
	foreach ($lines as $line) {
		if (strpos($line, ":") == False) continue;
		$name = substr($line, 0, strpos($line, ':'));
		$url = str_replace($name.":", "", $line);

		if (strpos($name, ";") > 1) $name = substr($name, 0, strpos($name, ";"));
		$output[$name] = $url;
	}
	return ($output);
}
