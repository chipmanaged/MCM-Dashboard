<html>  
<meta http-equiv="expires" content="0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
  <style>  
    h2 {
    color:#686868;
	}

div.outter {		float:left; 
					border:thin solid black;
					padding-top: 0px;  
					padding-right: 5px;  
					padding-bottom: 0in;  
					padding-left: 5px;  
					margin: 5px;}  

div.inner {			float:left;
//					height: 275px;
					width: 160px;
					padding-bottom: 20px;}  

div.innersmall {	float:left; 
					width: 205px;}  

div.outterarchive {	background: #BABABA; 
					float:left;
					border:thin solid black;
					padding-top: 0px;  
					padding-right: 5px;  
					padding-bottom: 0in;  
					padding-left: 5px;  
					margin: 5px;}  

div.innerarchive {	background: #BABABA;
					float:left; 
//					height: 275px;
					width: 160px;}  

img.MaxSized {		max-width: 150px;
					max-height:35%;}

input[type="number"] {width:30px;}

input[type="text"] {width:43px;}	

  </style> 
</head>  

<body>  

<?php

// ................................................................................................

include 'settings-userinfo.php';

Beeminder_Autoloader::register();
// ................................................................................................

include 'settings-groups.php';

// ................................................................................................

include 'settings-umbrellas.php';

// ................................................................................................


// Create new client
	$api = new Beeminder_Client();
// Setup auth (private token)
	$api->login($usr, $key, Beeminder_Client::AUTH_PRIVATE_TOKEN);
// Fetch a list of goals for the user
	$goals = $api->getGoalApi()->getGoals();
// Time Conversion
	function convertTime($dec){
		$hour = floor($dec);
		$min = round(60*($dec - $hour));}
// Time-related
	$date = new DateTime();
	$startToday = strtotime('today');
	$endToday = strtotime('tomorrow');
	$endTomorrow = $endToday + (24 * 60 * 60);
	$endWeek = $endToday + (6 * 24 * 60 * 60);
	$fourWeeks = $endToday + (27 * 24 * 60 * 60);
	$countDays = 0;
	$countDiff = 0;
	$countAver = 0;
	$Yesterday = $startToday - (24 * 60 * 60);
// Data Entry
	$mySlug = $_POST['theSlug'];
	$myPoint = $_POST['myPoint'];
	$myComment = $_POST['myComment'];
	$enterPoint = $myPoint;

// ENTER RECENT DATAPOINT
// Enter datapoint into umbrella goals
echo "<div class=\"outter\"> <center>";
foreach ($goals as $goal) {
	$cU = 0;

// ................................................................................................

while ($cU < $countUmbrellas) {
	if (
		(in_array($mySlug, $$umbrellaArray[$cU]))
		&& $goal->slug == $mySlug 
		) {
			$enter  = $api->getDatapointApi()->createDatapoint($umbrellaArray[$cU+1], $enterPoint, $comment = "{$myComment} (auto from {$mySlug})", $timestamp = null, $sendmail = true);
			}
	$cU = $cU+2;
}
// Enter datapoint into its own goal and send info back to dash
if ($goal->slug == $mySlug) {
		echo "<div class=\"inner\">";
		$enter  = $api->getDatapointApi()->createDatapoint($goal->slug, $enterPoint, $comment = "{$myComment}", $timestamp = null, $sendmail = true);
		echo "<b> {$goal->title}</b> \n <br>";
		echo "Value: {$myPoint} \n <br>";
		echo "Comment: {$myComment} \n <br>";
		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
				echo "</div>";
	}
}
echo "</center></div>";


// OUTPUT TO DASH
// Output the goals by user-specified group
$cG = 0;
while ($cG < $countGroups) {
		echo "<div class=\"outter\"> <center>";
		echo "<h2>{$groupsArray[$cG+1]}</h2>";
		foreach ($goals as $goal) {
			if (
				(in_array($goal->slug, $$groupsArray[$cG]))
				&& (($goal->losedate < $endToday)
					|| ((in_array($goal->slug, $enterDaily)) && ($goal->lastday < $startToday)))
			){
				echo "<div class=\"inner\">";
				echo "<b> {$goal->title}</b> \n <br>";
				echo "{$goal->limsum} \n <br>";
				echo "<form method=\"POST\" action=\"dashboard.php\">";
				echo "<input type=\"hidden\" name=\"theSlug\" value=\"{$goal->slug}\">";
				echo "<input type=\"text\" name=\"myPoint\" />";
				echo " - <input type=\"text\" name=\"myComment\" />";
				echo " <input type=\"submit\" value=\"Enter!\">";
				echo "</form>";
				echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
								echo "</div>";
					}
				}
		echo "</center></div>";
		$cG = $cG+2;
}

// Output TODAY'S DERAIL RISKS
echo "<div class=\"outter\"> <center>";
echo "<h2>Could Derail Today</h2>";
foreach ($goals as $goal) {
	if (
		// NAME
		($goal->losedate < $endToday)
		){
		echo "<div class=\"inner\">";
		echo "<b> {$goal->title}</b> \n <br>";
		echo "{$goal->limsum} \n <br>";
		echo "<form method=\"POST\" action=\"dashboard.php\">";
		echo "<input type=\"hidden\" name=\"theSlug\" value=\"{$goal->slug}\">";
		echo "<input type=\"text\" name=\"myPoint\" />";
		echo " - <input type=\"text\" name=\"myComment\" />";
		echo " <input type=\"submit\" value=\"Enter!\">";
		echo "</form>";
		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
				echo "</div>";
	}
}
echo "</center></div>";

// UPPER LIMITS
echo "<div class=\"outter\"> <center>";
echo "<h2>Keep an Eye on Upper Limits</h2>";
foreach ($goals as $goal) {
	if ($goal->yaw == "-1"){
		echo "<div class=\"inner\">";
		echo "<b> {$goal->title}</b> \n <br>";
		echo "{$goal->limsum} \n <br>";
		echo "<form method=\"POST\" action=\"dashboard.php\">";
		echo "<input type=\"hidden\" name=\"theSlug\" value=\"{$goal->slug}\">";
		echo "<input type=\"text\" name=\"myPoint\" />";
		echo " - <input type=\"text\" name=\"myComment\" />";
		echo " <input type=\"submit\" value=\"Enter!\">";
		echo "</form>";
		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
				echo "</div>";
			}
		}
echo "</center></div>";

// COULD DERAIL TOMORROW
echo "<div class=\"outter\"> <center>";
echo "<h2>Could Derail Tomorrow</h2>";
foreach ($goals as $goal) {
	if (
		// NAME
		($endToday < $goal->losedate) && 
		($goal->losedate < $endTomorrow) &&
//		($goal->burner != "backburner") && 
		($goal->frozen != 1)
		) {
		echo "<div class=\"inner\">";
		echo "<b> {$goal->title}</b> \n <br>";
		echo "{$goal->limsum} \n <br>";
		echo "<form method=\"POST\" action=\"dashboard.php\">";
		echo "<input type=\"hidden\" name=\"theSlug\" value=\"{$goal->slug}\">";
		echo "<input type=\"text\" name=\"myPoint\" />";
		echo " - <input type=\"text\" name=\"myComment\" />";
		echo " <input type=\"submit\" value=\"Enter!\">";
		echo "</form>";
		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
				echo "</div>";
	}
}
echo "</center></div>";

// ALL ACTIVE GOALS
echo "<div class=\"outter\"> <center>";
echo "<h2>All Active Goals</h2>";
foreach ($goals as $goal) {
	if (
		($goal->burner != "backburner")
		) {
		echo "<div class=\"inner\">";
		echo "<b> {$goal->title}</b> \n <br>";
		echo "{$goal->limsum} \n <br>";
		echo "<form method=\"POST\" action=\"dashboard.php\">";
		echo "<input type=\"hidden\" name=\"theSlug\" value=\"{$goal->slug}\">";
		echo "<input type=\"text\" name=\"myPoint\" />";
		echo " - <input type=\"text\" name=\"myComment\" />";
		echo " <input type=\"submit\" value=\"Enter!\">";
		echo "</form>";
		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
				echo "</div>";
	}
}
echo "</center></div>";

// 'ON HOLD' OR 'TO BE ARCHIVED' (i.e. Backburner / Below the Line)
echo "<div class=\"outterarchive\"> <center>";
echo "<h2>'On Hold' or 'To Be Archived'</h2>";
foreach ($goals as $goal) {
	if (
		// NAME
		($goal->burner == "backburner")
		) {
		echo "<div class=\"innerarchive\">";
		echo "<b> {$goal->title}</b> \n <br>";
		echo "{$goal->limsum} \n <br>";
		echo "<form method=\"POST\" action=\"dashboard.php\">";
		echo "<input type=\"hidden\" name=\"theSlug\" value=\"{$goal->slug}\">";
		echo "<input type=\"text\" name=\"myPoint\" />";
		echo " - <input type=\"text\" name=\"myComment\" />";
		echo " <input type=\"submit\" value=\"Enter!\">";
		echo "</form>";
		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\"><img class=\"MaxSized\" src=\"{$goal-> thumb_url}\" alt=\"{$goal->title}\"></a> \n <br>";
				echo "</div>";
			}
		}
	echo "</center></div>";




?>




</body>  
</html> 
