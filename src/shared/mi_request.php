<?php
error_reporting(0);

function moneyinst_get_domain(){
  $cacheTime = 60;
  $cacheFile = '/tmp/moneyinst-domain.tmp';
  $domain = FALSE;
  clearstatcache();
  if (@file_exists($cacheFile)) {
    $tmp = @file_get_contents($cacheFile);
    if ($tmp !== false) {
        $domain = $tmp;
    }
    $mTime = @filemtime($cacheFile);
    if ((time() - $mTime) > $cacheTime || $mTime == FALSE) {
      $domain = FALSE;
    }
  }
  if (!$domain){
    $domain = @file_get_contents('http://api.moneyinst.com/api/frontend_domain/');
    if (!filter_var($domain, FILTER_VALIDATE_URL)){
      return FALSE;
    }
    $fp = @fopen($cacheFile, "w");
    if ($fp && @flock($fp, LOCK_EX)){
      ftruncate($fp, 0);
      fwrite($fp, $domain);
      fflush($fp);
      flock($fp, LOCK_UN);
    }
  }
  return $domain;
}

$domain = moneyinst_get_domain();
if (!$domain){
  die;
}

$apiUrl = $domain . '/api/download_url/' . base64_decode($_REQUEST['type']) . '/' . base64_decode($_REQUEST['sid']) .
'/' . urlencode(base64_decode($_REQUEST['name'])) . '/' . urlencode(base64_decode($_REQUEST['url']));
$url = file_get_contents($apiUrl);
if (!filter_var($url, FILTER_VALIDATE_URL)){
  header('Location: ' . urldecode($_REQUEST['href']));
}
header('Location: ' . $url);
die;
?>