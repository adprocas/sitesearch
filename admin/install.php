<?php

require_once('../includes/config.php');
require_once('includes/site_search_admin.php');


$siteSearchAdmin = new SiteSearchAdmin();
$siteSearchAdmin->setup_tables();

?>