<?php


function moneyinst_url($url, $name, $size)
{
    include_once('moneyinst.php');
    global $member_id;
    global $_moneyinst;
    $res = $_moneyinst->createUrl($url, $member_id['user_group'], $name, $size, true);
    return $res;
}
