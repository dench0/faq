<?php

include_once('moneyinst.php');

$static_result['template'] = $_moneyinst->replaceLinks($static_result['template'], $member_id['user_group']);