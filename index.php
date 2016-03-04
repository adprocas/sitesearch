<?php

require_once('includes/config.php');
require_once('smarty-connect.php');
require_once('includes/db_class.php');
$page = 0;
$limit = 15;
if(isset($_GET["p"])) {
	if($_GET["p"] <= 1) {
		$page = 0;
	} else {
		$page = $_GET["p"]-1;
	}
}
if(isset($_GET["q"])) {
	$search_term = $_GET["q"];	
} else {
	$search_term = "";
}
$result = null;

$smarty->assign('name','Ned');
$smarty->assign('search_term',$search_term);

if(isset($search_term) && $search_term != "") {
	$db = new db_class();
	$result = $db->search_pages_for_text($search_term, $limit, $page*$limit);
} else if(isset($_GET["q"])) {
	$db = new db_class();
	$result = $db->search_pages_for_text($search_term, $limit, $page*$limit);
}

$rowsTotal = $db->get_calc_found_rows();

$pages = $rowsTotal/$limit;

$smarty->assign('pages', $pages);
$smarty->assign('search_term', $search_term);
$smarty->assign('search_results', $result);

$smarty->assign('total_rows', $rowsTotal);
//** un-comment the following line to show the debug console
//$smarty->debugging = true;

$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

// clear only cache for index.tpl
$smarty->clearCache('index.tpl');

$smarty->display('index.tpl');

?>