<?php

// ***** ADD DATA TO MORE THAN ONE GOAL ***** 
// This is to list the goals that need to add data to more than one goal at a time
// The umbrella array should be set up with the name of the array for each umbrella goal's slug (I use "slugUA", so that it's not at risk of being used elsewhere) and then the slug of the umbrella goal
// (Umbrella goals are the goals to which data will be added when entered into other goals.)

$umbrellaArray = array ("workUA", "work",
						"projectsUA", "projects");

// Use the same name for each array as you listed above in $umbrellaArray and then list all of the sub-goals that will be adding data to the main goal.
$workUA = array (
				"project1",
				"project2", 
				"projects");

$projectsUA = array (
				"project1",
				"project2");

$countUmbrellas = count($umbrellaArray);

?>