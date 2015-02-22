<?php
$sid = 1;//замените на ваш сайт ид (узнать можно на сайте партнерской программы)

error_reporting(0);

if (!isset($_REQUEST['file'])){
  die;
}

$href = base64_decode($_REQUEST['file']);

if (isset($_REQUEST['name'])){
  $name = base64_decode($_REQUEST['name']);
}

if (!isset($name)){
  if (!preg_match('@^(?:[^/]*://)?+([^/]*+).*?([^/]*?([^\./?]*))(?:[?].*)?\/?$@', $href, $linkmatches) || empty($linkmatches[2])){
    header('Location: ' . $href);
    die;
  }else{
    $name = $linkmatches[2];
  }
}
if (!preg_match('/^.+\.([^.]+)$/', $name, $ext)){
  header('Location: ' . $href);
  die;
};

$ext = $ext[1];
$ext = find_code_by_ext('.' . $ext . '.');

$domain = moneyinst_get_domain();
if (!$domain){
  header('Location: ' . $href);
  die;
}
$apiUrl = $domain . '/api/download_url/' . $ext . '/' . $sid . '/' . urlencode($name) . '/' . urlencode($href);

$url = file_get_contents($apiUrl);
if (!filter_var($url, FILTER_VALIDATE_URL)){
  header('Location: ' . $href);
  die;
}
header('Location: ' . $url);


function moneyinst_get_domain(){
  $cacheTime = 60;
  $cacheFile = sys_get_temp_dir() . '/moneyinst-domain.tmp';
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
      @ftruncate($fp, 0);
      @fwrite($fp, $domain);
      @fflush($fp);
      @flock($fp, LOCK_UN);
    }
  }
  return $domain;
}

function find_code_by_ext($ext){
  $types_lists = array('archive' => '.7z.bz2.cab.deb.jar.rar.rpm.tar.zip.',
                   'video'   => ".3gp.aaf.asf.flv.mkv.mov.mpeg.qt.wmv.hdv.mpeg4.mp4.dvd.mxf.avi.",
                   'audio'   => ".aac.asf.cda.fla.mp3.ogg.wav.wma.cd.ac3.dts.flac.midi.mod.aud.",
                   'image'   => ".bmp.cpt.gif.jpeg.jpg.jp2.pcx.png.psd.tga.tpic.tiff.tif.wdp.hdp.cdr.svg.ico.ani.cur.xcf.",
                   'torrent' => ".torrent.",
                   'android' => ".apk.",
                   'book'    => ".ps.eps.pdf.doc.txt.rtf.djvu.opf.chm.sgml.xml.fb2.fb3.tex.lit.exebook.prc.epub.",
                   'disk'    => ".img.iso.nrg.mdf.uif.bin.cue.daa.pqi.cso.ccd.sub.wim.swm.rwm.");

  $types_codes = array('archive' => 1,
                   'video'   => 3,
                   'audio'   => 4,
                   'image'   => 5,
                   'torrent' => 6,
                   'android' => 7,
                   'book'    => 8,
                   'disk'    => 9,);

  foreach($types_lists as $key => $list){
    if (strpos($list, $ext) !== false){
      return $types_codes[$key];
    }
  }
  return 2;
}

?>
