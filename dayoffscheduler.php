<html>
<body>

<?php

// ................................................................................................

include 'settings-userinfo.php';

Beeminder_Autoloader::register();
// ................................................................................................

// Create new client
$api       = new Beeminder_Client();
// Setup auth (private token)
$api->login($usr, $key, Beeminder_Client::AUTH_PRIVATE_TOKEN);
// Fetch a list of goals for the user
$goals      = $api->getGoalApi()->getGoals();
// Time-related

date_default_timezone_set('US/Eastern');

$date       = new DateTime();
$startToday = strtotime('today');
$endToday   = strtotime('tomorrow');

$yesterday  = $startToday - (1 * 24 * 60 * 60);
$twoDaysAgo = $startToday - (2 * 24 * 60 * 60);
$oneWeekAgo = $startToday - (7 * 24 * 60 * 60);

$endTomorrow = $endToday + (24 * 60 * 60);
$inOneweek   = $endToday + (6 * 24 * 60 * 60);
$fourWeeks   = $endToday + (27 * 24 * 60 * 60);

$thisSaturday  = strtotime("next Saturday");
$thisSunday    = strtotime("next Sunday");
$thisMonday    = strtotime("next Monday");
$thisTuesday   = strtotime("next Tuesday");
$thisWednesday = strtotime("next Wednesday");
$thisThursday  = strtotime("next Thursday");
$thisFriday    = strtotime("next Friday");
// ................................................................................................

echo '<strong>WARNING</strong>: This is not yet setup to handle places where the road uses "goal total" values instead of rates (after the akrasia horizon).<br>Solution: If your goal gets all messed up because of this, change it to use a rate instead of a goal total and then use "take a break" to set a rate (that rate, I imagine) from the earliest day available until the last day of the goal. <br> Future versions will correct for this, but this is my interim solution.<br><br><br>';



// [ ] Needs a dropdown to select the goal to alter
$slug      = 'test';


// Data Entry
echo "<form method=\"POST\" action=\"dayoffscheduler.php\">";
echo "<input type=\"text\" name=\"theSlug\" value=\"SLUG\">";
echo "<br><br>Set every ";
echo "<select name=\"theBreakDay\" class=\"inputstandard\">";
	echo "<option value=\"default\"> Select Day </option>";
	echo "<option value=\"Saturday\" name=\"Saturday\"> Saturday </option>";
	echo "<option value=\"Sunday\" name=\"Sunday\"> Sunday </option>";
	echo "<option value=\"Monday\" name=\"Monday\"> Monday </option>";
	echo "<option value=\"Tuesday\" name=\"Tuesday\"> Tuesday </option>";
	echo "<option value=\"Wednesday\" name=\"Wednesday\"> Wednesday </option>";
	echo "<option value=\"Thursday\" name=\"Thursday\"> Thursday </option>";
	echo "<option value=\"Friday\" name=\"Friday\"> Friday </option>";
echo "</select>";
echo "   to have a rate of ";
echo "<input type=\"text\" name=\"theBreakRate\" value=\"0\">";
echo " <input type=\"submit\" value=\"Enter!\">";
echo "</form>";

$slug      = $_POST[theSlug];
$breakRate = $_POST[theBreakRate];
$breakDay  = $_POST[theBreakDay];
$breakRate = $breakRate + 0;

foreach ($goals as $goal) {
	if (($goal->slug == $slug)) {
		
		// Get the existing roadall array, the goal date, and the end of the akrasia horizon
		$roadArray = $goal->roadall;
		// $inOneweek // End of the akrasia horizon
		$goalDate  = $goal->goaldate;		
		$newRoadArray = array();
		
		// Figure out the first break date
		if ($breakDay == "Saturday") {
			$breakDate = $thisSaturday + (7 * 24 * 60 * 60);
		} elseif ($breakDay == "Sunday") {
			$breakDate = $thisSunday + (7 * 24 * 60 * 60);
		} elseif ($breakDay == "Monday") {
			$breakDate = $thisMonday + (7 * 24 * 60 * 60);
		} elseif ($breakDay == "Tuesday") {
			$breakDate = $thisTuesday + (7 * 24 * 60 * 60);
		} elseif ($breakDay == "Wednesday") {
			$breakDate = $thisWednesday + (7 * 24 * 60 * 60);
		} elseif ($breakDay == "Thursday") {
			$breakDate = $thisThursday + (7 * 24 * 60 * 60);
		} elseif ($breakDay == "Friday") {
			$breakDate = $thisFriday + (7 * 24 * 60 * 60);
		}

		// use row 1 from original road
		$newRow0   = array();
		$newRow0[] = $roadArray[0][0];
		$newRow0[] = $roadArray[0][1];
		$newRow0[] = $roadArray[0][2];
		array_push($newRoadArray, $newRow0);
		
		// set current original row date to the second row (row 1)
		$oPosition = 1;
		$countO    = count($roadArray);

		// while (there are still rows left in the original array)
		while ($oPosition < $countO) {
//			 if breakdate is smaller than end date
//			if (date('y-m-d', $breakDate) < $goalDate) {				
//				 Is new larger?
				if (date('y-m-d', $breakDate) > date('y-m-d', $roadArray[$oPosition][0])) {
					// yes
					// enter original row
					$newRow1   = array();
					$newRow1[] = $roadArray[$oPosition][0];
					$newRow1[] = $roadArray[$oPosition][1];
					$newRow1[] = $roadArray[$oPosition][2];
					array_push($newRoadArray, $newRow1);
					// move on to the next original row
					$oPosition = $oPosition + 1;
					// no
					// is new equal?
				}  elseif (date('y-m-d', $breakDate) == date('y-m-d', $roadArray[$oPosition][0])) {
					// yes
					// replace original with new row (by ignoring original and entering the new row)
					// check to see if the day before was the same date as the new row and, if so, ignore this piece, if not, enter it
					if (date('y-m-d', ($breakDate - (1 * 24 * 60 * 60))) != date('y-m-d', $roadArray[$oPosition - 1][0])) {
						$newRow2   = array();
						$newRow2[] = ($breakDate - (1 * 24 * 60 * 60));
						$newRow2[] = $roadArray[$oPosition][1];
						$newRow2[] = $roadArray[$oPosition][2];

						array_push($newRoadArray, $newRow2);
					}
					$newRow3   = array();
					$newRow3[] = $breakDate;
					$newRow3[] = null;
					$newRow3[] = $breakRate;
					array_push($newRoadArray, $newRow3);
					// move on to the next original row
					$oPosition = $oPosition + 1;
					// then move on to the next break date
					$breakDate = $breakDate + (7 * 24 * 60 * 60);
					// no
					// is new smaller?
				} elseif (date('y-m-d', $breakDate) < date('y-m-d', $roadArray[$oPosition][0])) {
					// yes
					// enter new row
					// check to see if the day before was the same date as the new row. If it wasn't, enter the moved row.
//					$lessOne = ;
					if (date('y-m-d', $breakDate - (1 * 24 * 60 * 60)) != date('y-m-d', $roadArray[$oPosition - 1][0])) {
						$newRow5   = array();
						$newRow5[] = ($breakDate - (1 * 24 * 60 * 60));
						$newRow5[] = $roadArray[$oPosition][1];
						$newRow5[] = $roadArray[$oPosition][2];
						array_push($newRoadArray, $newRow5);
					}
					$newRow4   = array();
					$newRow4[] = $breakDate;
					$newRow4[] = null;
					$newRow4[] = $breakRate;
					array_push($newRoadArray, $newRow4);					
					// then move on to the next break date
					$breakDate = $breakDate + (7 * 24 * 60 * 60);
				} else {
					// no
					// So very broken
					echo 'Something\'s broken. Very, very broken <br><br>';
					$oPosition = $oPosition + 1;

				}
//			}
		}
		
		$successMessage = 0;
		try {
			$api->getGoalApi()->editGoal($goal->slug, array(
				'roadall' => json_encode($newRoadArray)
			));
		}
		catch (Exception $e) {
			$successMessage = 1;
		}
		if ($successMessage == 0) {
			echo "Changed the road!\n <br>";
			echo "<b> {$goal->title}</b> \n <br>";
			echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br><br><br>";

			
		} else {
			echo "Failed to change the road (probably not enough safety buffer)!\n";
			echo '<br>This is what I tried to send :: <br>';
			print_r(json_encode($newRoadArray));
			echo '<br><br>';
			echo "<b> {$goal->title}</b> \n <br>";
			echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" <b> {$goal->title}</b></a> \n <br><br><br>";
			echo '<br>';
			
		}
		
	}
}
// ................................................................................................


?>

</body>
</html>