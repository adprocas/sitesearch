<?php

require_once('../includes/config.php');
require_once('includes/functions.php');
require_once('includes/smarty_connect.php');
require_once('includes/site_search_admin.php');

set_time_limit(120);
$page = "status";
$siteSearchAdmin = new SiteSearchAdmin();
$siteSearchAdmin->setup_tables();
$message = "";
$crawl_table_empty = "";
$crawl_list = "";
$recrawl = "";
$id = 0;

if(isset($_GET["id"])) {
	$id = $_GET["id"];
	$recrawl = $_GET["action"];
	if($recrawl == "recrawl") {
		$siteSearchAdmin->update_url_to_crawl_list_by_id($id, 1);
	} else if($recrawl == "delete") {
		$siteSearchAdmin->update_url_to_crawl_list_by_id($id, 4);
	}
}

$checked_login = $siteSearchAdmin->check_login();
if($checked_login == 2) {
	$crawl_list = $siteSearchAdmin->get_status_list();
}

$smarty->assign('checked_login', $checked_login);
$smarty->assign('message', $message);
$smarty->assign('page', $page);
$smarty->assign('crawl_list', $crawl_list);
$smarty->assign('crawl_table_empty', $crawl_table_empty);
$smarty->assign('changed', $id);
$smarty->assign('recrawl', $recrawl);
if($id > 0) {
	$smarty->clearCache('recrawl.tpl');
	$smarty->display('recrawl.tpl');
}



///flow
//////enter starting url - this will be the first 

?>

