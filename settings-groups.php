<?php

// ***** CREATE YOUR GOAL GROUPS ***** 
// Array should be set up with each group's array name and then the group title you want printed followed by next array name and so on
// List the arrays in the order you want them to be displayed.
$groupsArray = array (  "Morning", "Morning Goals",
						"Work", "Work Goals",
						"Evening", "Evening Goals");

// Use the same name for each array as you listed above in $groupsArray and then list the slugs of the goals that belong in that group
$Morning = array (
					"cardio", 
					"wakeearly", 
					"weight");

$Work = array (
					"email", 
					"projects", 
					"workhours");

$Evening = array (
					"floss", 
					"meditation", 
					"hobby", 
					"sleepearly");


$countGroups = count($groupsArray);
// ................................................................................................

// ***** LIST THE GOALS THAT NEED DAILY DATA ***** 
// List the slugs for the goals that need a datapoint entered every day (e.g.: anything with pess. presumptive turned on)
$enterDaily = array(
					"email", 
					"sleepearly",										
					"wakeearly", 
					"weight");										
					// [] to do: is pessimistic presumptive true/false available to api?

?>