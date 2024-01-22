<?PHP

if (file_exists('password_protect.php') == True) include ('password_protect.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);

include ('auto_update.php');
include ('ics_parser.php');
include ('additionalfunctions.php');

if (isset($_GET['calyear'])) $calyear = htmlspecialchars($_GET['calyear']);
else $calyear = date("Y");
if (isset($_GET['calmonth'])) $calmonth = htmlspecialchars($_GET['calmonth']);
else $calmonth = date("m");

if ((intval($calmonth) > 1)and(intval($calmonth) < 12)) {
	$prevmonth = twodigits(strval(intval($calmonth) - 1));
	$prevyear = $calyear;
	$nextmonth = twodigits(strval(intval($calmonth) + 1));
	$nextyear = $calyear;
}
else if (intval($calmonth) == 1){
	$prevmonth = 12;
	$prevyear = strval(intval($calyear) - 1);
	$nextmonth = twodigits(strval(intval($calmonth) + 1));
	$nextyear = $calyear;
}
else if (intval($calmonth) == 12){
	$prevmonth = twodigits(strval(intval($calmonth) - 1));
	$prevyear = $calyear;
	$nextmonth = 1;
	$nextyear = strval(intval($calyear) + 1);
}

get_calendars();
checkdir('./calendars');
$calendar = array();

foreach ($files as $file) {
	$calendar_str = file_get_contents("calendars/".$file.".ics");
	$calendar[$file] = parse_ics($calendar_str);
}

## Get all events
$events = array();
foreach ($calendar as $key => $value) {
	foreach ($value['events'] as $event) {
		$events[count($events) + 1] = array();
		$events[count($events)]['SUMMARY'] = $event['SUMMARY'];
		$events[count($events)]['DTSTART'] = strtotime($event['DTSTART']);
		$events[count($events)]['DTSTART_STRING'] = $event['DTSTART'];
		$events[count($events)]['DTEND'] = strtotime($event['DTEND']);
		$events[count($events)]['DTEND_STRING'] = $event['DTEND'];
		$events[count($events)]['DURATION'] = $events[count($events)]['DTEND'] - $events[count($events)]['DTSTART'];
		if (isset($event['DESCRIPTION'])) $events[count($events)]['DESCRIPTION'] = $event['DESCRIPTION'];
		$events[count($events)]['CALENDAR'] = $key;
	}
}

?><!DOCTYPE html>
<html>
<head>
	<title>Calendar</title>
	<link rel="stylesheet" type="text/css" href="style/calendars.css" />
	<link rel="shortcut icon" href="./calendar-logo.png" /> 
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=480, initial-scale=0.7" />
</head>
<body>

	<header>
		<h1>Calendars</h1>
	</header>

	<main>

		<section id="calendar">
			<h2>Events
				<a href="./?calyear=<?PHP echo $nextyear; ?>&amp;calmonth=<?PHP echo $nextmonth; ?>">><span>Next Month</span></a>
				<a href="./?calyear=<?PHP echo $prevyear; ?>&amp;calmonth=<?PHP echo $prevmonth; ?>"><<span>Previous Month</span></a>&nbsp;&nbsp;&nbsp;
			</h2>
			<?PHP include ('_calendar.php'); ?>
		</section>

	</main>

	<aside>
		
		<?PHP usort($events, 'sortByStarttime'); ?>
		<section id="upcomingevents">
			<h2>Upcoming</h2>
			<ul>
			<?PHP
			$rightnow = idate("U");
			foreach ($events as $event) {
				if ($event['DTSTART'] > $rightnow) {
					echo '<li><span style="background:#'.stringToColorCode($event['CALENDAR']).';"></span><span>'.$event['SUMMARY'].'</span><span style="float:right;">('.add_hyphens_to_date(substr($event['DTSTART_STRING'], 0, 8)).')</span></li>';
				}
			}
			?>
			</ul>
		</section>

		<section id="calendarlist">
			<h2>Calendars</h2>
			<ul>
			<?PHP
			foreach (array_keys($calendar) as $key) {
				echo '<li><span style="background:#'.stringToColorCode($key).';"></span><span>'.$key.'</span></li>';
			}
			?>
			</ul>
		</section>

	</aside>

	<footer>

	</footer>

</body>
</html>
