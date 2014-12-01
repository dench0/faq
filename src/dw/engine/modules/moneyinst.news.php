<?php

include_once('moneyinst.php');

$tpl->result['content'] =  $_moneyinst->replaceLinks($tpl->result['content'], $member_id['user_group']);