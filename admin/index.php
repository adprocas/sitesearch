<?php
session_start();
require_once('../includes/config.php');
require_once('includes/functions.php');
require_once('includes/smarty_connect.php');
require_once('includes/site_search_admin.php');

set_time_limit(120);

$page = "home";

$siteSearchAdmin = new SiteSearchAdmin();
$siteSearchAdmin->setup_tables();
$message = "";
$forgot = false;
$emailsent = false;
if(isset($_GET["forgot"])) {
	if($_GET["forgot"] == true) {
		$forgot = true;
	}
}

if(isset($_POST["forgotpassword"]) && isset($_POST["email"])) {
	if($siteSearchAdmin->account_exists($_POST["email"])) {
		//$message = $siteSearchAdmin->reset_password($_POST["email"]);//this was used for when mail() wasn't working
		$message = "Please check your email for a new password";
		$emailsent = true;
	}
}

if( isset($_POST["enteradmin"]) ) {
	if( is_valid_value($_POST["username"])==true && is_valid_value($_POST["email"])==true && is_valid_value($_POST["password"])==true && is_valid_value($_POST["repassword"])==true && ( $_POST["password"] == $_POST["repassword"] ) ) {
		$username = $_POST["username"];
		$email = $_POST["email"];
		$password = $_POST["password"];

		$siteSearchAdmin->create_admin($username, $password, $email);
	} else {
		$message .= "Form not filled out properly - Please Try Again<br>";
	}
}
if( isset($_POST["enterlogin"])) {
	if(is_valid_value($_POST["username"])==true && is_valid_value($_POST["password"])==true) {
		$siteSearchAdmin->login($_POST["username"], $_POST["password"]);
	}
}
$checked_login = $siteSearchAdmin->check_login();

$smarty->assign('emailsent', $emailsent);
$smarty->assign('forgot', $forgot);
$smarty->assign('checked_login', $checked_login);
$smarty->assign('message', $message);

$smarty->assign('page', $page);

$smarty->clearCache('index.tpl');

$smarty->display('index.tpl');
?>