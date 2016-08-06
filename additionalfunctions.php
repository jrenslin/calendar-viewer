<?PHP 

function twodigits ($string) {
	if (strlen(strval($string)) == 1) $string = '0'.strval($string);
	else $string = strval($string);
	return $string;
}		

function fourdigits ($string) {
	if (strlen(strval($string)) == 3) $string = '0'.strval($string);
	else $string = strval($string);
	return $string;
}

function strmonth ($string) {
	$month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$string = $month[intval($string)-1];
	return $string;
}

# This function checks a folder ($folder) and returns all files with the extension $extension in it
function checkdir ($folder, $extensions = array('ics')){
			
	global $files; 
		
	if ($handle = opendir($folder)) {
		while (false !== ($entry = readdir($handle))) {
			if (!((is_file($folder.'/'.rtrim($entry, '.')) == false)and(strpos(' '.$entry, '.') == 0))){
				if (in_array(strtolower(pathinfo($entry, PATHINFO_EXTENSION)), $extensions)) $files[] = str_replace('.'.pathinfo($entry, PATHINFO_EXTENSION), '', pathinfo($entry, PATHINFO_BASENAME));
			}
		}
	}
}

function add_hyphens_to_date ($input) {
	return (substr($input, 0, 4).'-'.substr($input, 4, 2).'-'.substr($input, 6, 2));
}

function sortByStarttime($a, $b) {
    return $a['DTSTART'] - $b['DTSTART'];
}

# Thanks to user user217562 on stackoverflow for this function (https://stackoverflow.com/questions/3724111/how-can-i-convert-strings-to-an-html-color-code-hash). It's not perfect, but it is sufficient for my purposes here.
function stringToColorCode($str) {
  $code = dechex(crc32($str));
  $code = substr($code, 0, 6);
  return $code;
}

?>
