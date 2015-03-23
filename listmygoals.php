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
	$mySlug = $_POST[theSlug];
	$myPoint = $_POST[myPoint];
	$myComment = $_POST[myComment];
	$enterPoint = $myPoint;


// Output list of goal slugs
foreach ($goals as $goal) {

		echo "{$goal->title} ({$goal->slug})<br> ";
	
}



?>




</body>  
</html> 