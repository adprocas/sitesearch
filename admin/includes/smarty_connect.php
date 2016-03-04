<?php

require_once('../smarty/libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->setTemplateDir('templates/');
$smarty->setCompileDir('templates_c/');
$smarty->setConfigDir('configs/');
$smarty->setCacheDir('cache/');

$smarty->caching = 1;
$smarty->compile_check = true;