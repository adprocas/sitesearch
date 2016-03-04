<?php

require_once('../includes/config.php');
require_once('includes/functions.php');
require_once('includes/smarty_connect.php');
require_once('includes/site_search_admin.php');

set_time_limit(120);
$page = "crawler";
$siteSearchAdmin = new SiteSearchAdmin();
$siteSearchAdmin->setup_tables();
$message = "";
$crawl_list = "";
$crawl_report = "";
$crawl_table_empty = "";
$crawl_list = "";

$checked_login = $siteSearchAdmin->check_login();
if($checked_login == 2) {
	$crawl_list = $siteSearchAdmin->get_to_crawl_list();
	if(isset($_GET["startcrawl"])) {
		if(isset($_GET["url"])) {
			// echo $_GET["url"];
			$siteSearchAdmin->insert_url_for_search(urldecode($_GET["url"]));
		}
		$crawl_url = $crawl_list[0]["url"];
		// echo "Next crawl URL = $crawl_url";
		$next_crawling_url = $crawl_list[0]["url"];
		?>
		<META HTTP-EQUIV="refresh" CONTENT="5; URL=crawler.php?startcrawl=true&url=<?php echo urlencode($next_crawling_url); ?>">
		<?php
	}
	$crawl_table_empty = $siteSearchAdmin->is_crawl_table_empty();
	if(isset($_POST["url"])) {
		echo "got here";
		$siteSearchAdmin->add_url_to_crawl_list($_POST["url"]);
		$crawl_table_empty = $siteSearchAdmin->is_crawl_table_empty();
	}
	$crawl_report = $siteSearchAdmin->crawl_report;
	$crawl_list = $siteSearchAdmin->get_to_crawl_list();
}


$smarty->assign('checked_login', $checked_login);
$smarty->assign('message', $message);
$smarty->assign('page', $page);
$smarty->assign('crawl_report', $crawl_report);
$smarty->assign('crawl_list', $crawl_list);
$smarty->assign('crawl_table_empty', $crawl_table_empty);
$smarty->clearCache('crawler.tpl');
$smarty->display('crawler.tpl');


///flow
//////enter starting url - this will be the first 

?>

