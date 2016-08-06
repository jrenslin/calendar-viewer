<?php
if (isset($_GET['sel'])) $sel = $_GET['sel'];

$numdinmo = cal_days_in_month(CAL_GREGORIAN, $calmonth, $calyear); // 31
$week = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

$daysarray = array();
for ($i = 1; $i <= $numdinmo; $i++) {
	$daysarray[] = twodigits($i);	
}
$currentday = 1;
$startdayfound = false;

echo '<table class="calendar">';
echo '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>';

$counter = 0;
$startno = 0;
$endno = 6;

while ($counter < 5) { 

	for ($i = $startno; $i <= $endno; $i++){
		if ($i == $startno)echo '<tr>';
		if (strval(date("l", mktime(0, 0, 0, $calmonth, $currentday, $calyear))) == $week[$i-$startno]) {
			$startdayfound = true;
			$fullcurrentday = $calyear.$calmonth.twodigits($currentday); 
			
			echo '<td #id="'.$fullcurrentday.'"'; if (date("Ymd") == $fullcurrentday) echo ' style="background:#eee;"'; echo '>'.PHP_EOL;
			echo '<span>'.$currentday.'</span>';
			
			if (date("Ymd") == $fullcurrentday) echo '<i>Today</i>';
			foreach ($events as $event) {
				if (substr($event['DTSTART_STRING'], 0, 8) == $fullcurrentday) {
					echo '<a style="width:'.(100 / 86400 * $event['DURATION']).'%;background:#'.stringToColorCode($event['CALENDAR']).';">';
					echo '<div>';
						echo '<h2>'.$event['SUMMARY'].'</h2>';
						echo '<dl>';
							echo '<dt>From</dt><dd>'.$event['DTSTART_STRING'].'</dd>';
							echo '<dt>Until</dt><dd>'.$event['DTEND_STRING'].'</dd>';
							if (isset($event['DESCRIPTION']) and trim($event['DESCRIPTION']) != '') echo '<dt>Description</dt><dd>'.$event['DESCRIPTION'].'</dd>';
						echo '</dl>';
					echo '</div>';
					echo '</a>';
}
			}

			echo '</td>';

			if (($startdayfound == true)and($currentday < $numdinmo)) $currentday = $currentday + 1;
		}
		else echo '<td></td>';
		if ($i == $endno)echo '</tr>';
	}

	$counter++; $startno = $startno + 7; $endno = $endno + 7;
}

echo '</table>';

?>

