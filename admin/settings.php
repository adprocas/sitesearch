<?php

require_once('../includes/config.php');
require_once('includes/functions.php');
require_once('includes/smarty_connect.php');
require_once('includes/site_search_admin.php');

set_time_limit(120);
$page = "crawled";
$siteSearchAdmin = new SiteSearchAdmin();
$siteSearchAdmin->setup_tables();
$message = "";
$settings_list = "";

$checked_login = $siteSearchAdmin->check_login();
if($checked_login == 2) {
	if(isset($_POST["entersettings"])) {
		$siteSearchAdmin->set_config($_POST["base_url"], $_POST["admin_url"]);
	}
// 	// $settings_list = $siteSearchAdmin->get_crawled_list();
// 	print_r($config["settings"]);
}
$config["settings"] = $siteSearchAdmin->get_config();

$smarty->assign('checked_login', $checked_login);
$smarty->assign('message', $message);
$smarty->assign('page', $page);
$smarty->assign('settings_list', $settings_list);
///settings
$smarty->assign('base_url', $config["settings"]["base_url"]);
$smarty->assign('admin_url', $config["settings"]["admin_url"]);
$smarty->clearCache('settings.tpl');
$smarty->display('settings.tpl');


///flow
//////enter starting url - this will be the first 

?>
