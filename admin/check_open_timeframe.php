<?php

// Report all PHP errors (see changelog)
error_reporting(E_ALL);

// Report all PHP errors
error_reporting(-1);

// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

echo "true";

if(isset($_GET["check_url"])) {
	set_time_limit(30);

} else {
	echo "false";
}

file_get_contents(urldecode($_GET["check_url"]));

?>