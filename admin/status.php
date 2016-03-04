<?php

require_once('../includes/config.php');
require_once('includes/functions.php');
require_once('includes/smarty_connect.php');
require_once('includes/site_search_admin.php');
require_once('recrawl.php');

set_time_limit(120);
$page = "status";
$siteSearchAdmin = new SiteSearchAdmin();
$siteSearchAdmin->setup_tables();
$message = "";
$crawl_table_empty = "";
$crawl_list = "";

$checked_login = $siteSearchAdmin->check_login();
if($checked_login == 2) {
	$crawl_list = $siteSearchAdmin->get_status_list();
}


$smarty->assign('checked_login', $checked_login);
$smarty->assign('message', $message);
$smarty->assign('page', $page);
$smarty->assign('crawl_list', $crawl_list);
$smarty->assign('crawl_table_empty', $crawl_table_empty);
$smarty->clearCache('status.tpl');
$smarty->display('status.tpl');


///flow
//////enter starting url - this will be the first 

?>

