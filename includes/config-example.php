<?php

//rename this file to "config.php" and modify for your settings below

error_reporting(0);
//////////////////////////
//COMMENT THE LINE ABOVE AND UNCOMMENT THE FOLLOWING TWO LINES FOR ERROR AND WARNING REPORTS
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//////////////////////////

require_once('db_class.php');


$config = Array(
		//currently there is only support for the mysql driver
		"database" => "mysql",

		//mysql database information
		"mysql_username" => "username",
		"mysql_password" => "password",
		"mysql_database" => "database",
		"mysql_host" => "localhost",

	);

$db = new db_class();

$config["settings"] = $db->get_config();

?>