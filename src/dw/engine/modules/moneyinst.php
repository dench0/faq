<?php

define('moneyinst_conffile', dirname(__FILE__) . '/../data/moneyinstconfig.php');

include_once(dirname(__FILE__) . '/../classes/moneyinst.class.php');

$_moneyinst = new MoneyInst();
$_moneyinst->loadConfig();

$js_array[] = "engine/classes/moneyinst/miobfs.js";
