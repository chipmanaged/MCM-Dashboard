<?php

$usr = 'USERNAME'; // ***** ENTER YOUR USERNAME ***** 
$key = 'KEY'; //  ***** ENTER YOUR KEY ***** (you can find it on the website under "advanced settings" in your profile)

// Path to image files (can be your public Dropbox folder, for example, or a folder on your server)
$imagePath = 'http://path/to/goalpics/'; // ***** ENTER THE PATH TO YOUR GOAL JPGS ***** 

// Include the autoloader
require_once 'lib/Beeminder/Autoloader.php'; // This assumes the library is in http://yourserver.com/Beeminder/lib/Beeminder/
// require_once '/path/to/lib/Beeminder/Autoloader.php';  // replace "/path/to/" with the actual path the API is stored in.

?>