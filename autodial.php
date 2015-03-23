<html>  
<meta http-equiv="expires" content="0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
  <style>  
div.outter {		float:left; 
					border:thin solid black;
					padding-top: 0px;  
					padding-right: 5px;  
					padding-bottom: 0in;  
					padding-left: 5px;  
					margin: 5px;}  

div.inner {			float:left;
					height: 275px;
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
					height: 275px;
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

include 'settings-autodialgoals.php';

// ................................................................................................


// Create new client
$api = new Beeminder_Client();

// Setup auth (private token)
$api->login($usr, $key, Beeminder_Client::AUTH_PRIVATE_TOKEN);



// Fetch a list of goals for the user
$goals = $api->getGoalApi()->getGoals();

$date = new DateTime();

$startToday = strtotime('today');
$endToday = strtotime('tomorrow');
$endTomorrow = $endToday + (24 * 60 * 60);
$endWeek = $endToday + (6 * 24 * 60 * 60);
$fourWeeks = $endToday + (27 * 24 * 60 * 60);
$Yesterday = $startToday - (24 * 60 * 60);
$lastWeek = $startToday - (7 * 24 * 60 * 60);
$fourWeeksAgo = $startToday - (28 * 24 * 60 * 60);
$in28Days = $startToday + (28 * 24 * 60 * 60);

$averageForRate = 0;
$newRate = 0;
$workDate = 0;
$workVal = 0;
$workRate = 0;

// ................................................................................................

// AUTODIALING
echo "<div class=\"outter\"> <center>";
echo "<h2>Auto-dialed Roads</h2>";
foreach ($goals as $goal) {
	if (in_array($goal->slug, $pleaseDial))
		{
		echo "<div class=\"outter\">";

		$myPoints = $api-> getDatapointApi()-> getGoalDatapoints($goal->slug, $datapoints_count = 320);
		$myTotal = 0;
		$mywAverage = 0;
		$myAverage = 0;
		$goalDate = $goal->goaldate;
		$tempPoints = array();
		foreach($myPoints as $thisPoint){
			if ($thisPoint->timestamp >= $fourWeeksAgo 
				&& $thisPoint->value != 0
			) {
			    $tempPoints[] = $thisPoint;
				$myTotal = $myTotal+($thisPoint->value);
				}
			}
		$countPoints = count($tempPoints);

		if ($goal->runits == "d") {
			$averageForRate = ($myTotal/28);
			}
		if ($goal->runits == "w") {
			$averageForRate = ($myTotal/28)*7;
			}
		if ($goal->runits == "m") {
			$averageForRate = ($myTotal/28)*365/12;
			}
		if ($goal->runits == "y") {
			$averageForRate = ($myTotal/28)*365;
			}
		$
		$change = $api->getGoalApi()->updateRoad($goal->slug, $rate = $averageForRate, $date = $goalDate, $value = null);

		echo "<a href=\"https://www.beeminder.com/{$usr}/{$goal->slug}\" target=\"_blank\">{$goal->title}</a> \n <br>";
		echo "old rate: {$goal->rate} /{$goal->runits}\n <br>";
		echo "new rate: {$averageForRate} /{$goal->runits}\n <br>";
		echo "</div>";
	}
}

echo "</center></div>";

?>


</body>  
</html> 