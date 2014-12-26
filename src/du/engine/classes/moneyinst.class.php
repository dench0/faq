<?php

class MoneyInst {
  private $config = array();
  private $errstr = 'Нет ошибок';
  private $fileTypes = array(
    'exe' => 'Не указывать',
    'mp3' => 'audio',
    'avi' => 'video',
    'exe' => 'setup',
    'pdf' => 'book',
    'torrent' => 'torrent',
    'rar' => 'archive',
    'iso' => 'disk',
    'jpg' => 'image'
  );

  private $typesLists = array(
    'archive' => '.7z.bz2.cab.deb.jar.rar.rpm.tar.zip.',
    'video' => ".3gp.aaf.asf.flv.mkv.mov.mpeg.qt.wmv.hdv.mpeg4.mp4.dvd.mxf.avi.",
    'audio' => ".aac.asf.cda.fla.mp3.ogg.wav.wma.cd.ac3.dts.flac.midi.mod.aud.",
    'image' => ".bmp.cpt.gif.jpeg.jpg.jp2.pcx.png.psd.tga.tpic.tiff.tif.wdp.hdp.cdr.svg.ico.ani.cur.xcf.",
    'torrent' => ".torrent.",
    'android' => ".apk.",
    'book' => ".ps.eps.pdf.doc.txt.rtf.djvu.opf.chm.sgml.xml.fb2.fb3.tex.lit.exebook.prc.epub.",
    'disk' => ".img.iso.nrg.mdf.uif.bin.cue.daa.pqi.cso.ccd.sub.wim.swm.rwm."
  );

  private $typesCodes = array(
    'archive' => 1,
    'video' => 3,
    'audio' => 4,
    'image' => 5,
    'torrent' => 6,
    'android' => 7,
    'book' => 8,
    'disk' => 9,
  );

  private function setArray($config, $par) {
    if (!isset($config[$par])) {
      $this->config[$par] = array();
    }
    else {
      $this->config[$par] = $config[$par];
    }
  }

  private function setInt($config, $par) {
    if (!isset($config[$par])) {
      $this->config[$par] = 0;
    }
    else {
      $this->config[$par] = intval($config[$par]);
    }
  }

  private function setIntMax($config, $par, $max) {
    if (!isset($config[$par])) {
      $this->config[$par] = 0;
    }
    else {
      $num = intval($config[$par]);
      if ($num <= 0 or $num > $max) {
        $this->config[$par] = 0;
      }
      else {
        $this->config[$par] = $num;
      }
    }
  }

  private function setBool($config, $par) {
    if (isset($config[$par]) and $config[$par] === TRUE) {
      $this->config[$par] = TRUE;
    }
    else {
      $this->config[$par] = FALSE;
    }
  }

  private function setString($config, $par) {
    if (isset($config[$par])) {
      $this->config[$par] = $config[$par];
    }
    else {
      $this->config[$par] = '';
    }
  }

  // loads configuration
  public function loadConfig() {
    if (is_file(moneyinst_conffile)) {
      include moneyinst_conffile;
    }
    // sid
    $this->setInt($config, 'sid');
    // hosts
    $this->setArray($config, 'hosts');
    // groups
    $this->setArray($config, 'groups');
    // state
    $this->setBool($config, 'on');
    // attachments
    $this->setBool($config, 'attach');
    // news
    $this->setBool($config, 'news');
    // static
    $this->setBool($config, 'static');
    // file type
    $this->setString($config, 'filetype');
    //ext
    $this->setArray($config, 'file_extensions');
  }

  // saves configuration
 public function saveConfig($on, $sid, $hosts, $groups, $attach, $news, $static, $filetype, $file_extensions) {
    // parsing int
    $psid = intval($sid);
    $pfiletype = $filetype;
    // parsing hosts
    $phosts = array();
    $hosts = trim($hosts);
    if (!empty($hosts)){
      $tmp_hosts = explode("\n", $hosts);
      foreach ($tmp_hosts as $host) {
        if (($tmp = trim($host)) != '') {
          $phosts[] = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $tmp));
        }
      }
      $phosts = array_unique($phosts);
    }
    // parsing groups
    $pgroups = array();
    if (isset($groups)) {
      foreach ($groups as $group) {
        $pgroups[] = intval($group);
      }
    }
    $pgroups = array_unique($pgroups);
    // parsing strings
    $file_extensions = str_replace(' ', '', $file_extensions);
    $file_extensions = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $file_extensions));
    $file_extensions = trim($file_extensions);
    $pfile_extensions = array();
    if (!empty($file_extensions)){
      $pfile_extensions = explode(',', $file_extensions);
      $pfile_extensions = array_unique($pfile_extensions);
    }  
    $str = '<?php

$config = array(
    \'on\' => ' . ($on ? 'true' : 'false') . ',
    \'attach\' => ' . ($attach ? 'true' : 'false') . ',
    \'news\' => ' . ($news ? 'true' : 'false') . ',
    \'static\' => ' . ($static ? 'true' : 'false') . ',
    \'sid\' => ' . $psid . ',
    \'hosts\' => array(';
    $tmp = '';
    foreach ($phosts as $host) {
      $tmp .= '
        \'' . $host . '\',';
    }
    $str .= substr($tmp, 0, -1) . '
    ),
    \'groups\' => array(';
    $tmp = '';
    foreach ($pgroups as $group) {
      $tmp .= '
        ' . $group . ',';
    }
    $str .= substr($tmp, 0, -1) . '
    ),
    \'filetype\' => \'' . $pfiletype . '\',
    \'file_extensions\' => array(';
    $tmp = '';
    foreach ($pfile_extensions as $ext) {
      $tmp .= '
        \'' . $ext . '\',';
    }
    $str .= substr($tmp, 0, -1) . '
    )
);';
    if (FALSE === file_put_contents(moneyinst_conffile, $str)) {
      $this->errstr = 'Не могу записать конфигурацию в файл "' . realpath(moneyinst_conffile) . '"';
      return FALSE;
    }
    return TRUE;
  }

  private function isHostAllowed($url){
    if (!count($this->config['hosts'])){
      return TRUE;
    }
    if (FALSE === preg_match('@^(?>https?://)?(.*?)(?>/|$)@', $url, $matches)) {
      return FALSE;
    }
    if (!in_array($matches[1], $this->config['hosts'])) {
      return FALSE;
    }
    return TRUE;
  }

  private function isUserAllowed($group){
    if (!in_array($group, $this->config['groups'])) {
      return FALSE;
    }
    return TRUE;
  }

  private function isExtensionAllowed($name){
    if (count($this->config['file_extensions'])) {
      if (preg_match('/\.([\w]+)$/', $name, $matches)) {
        if (!in_array($matches[1], $this->config['file_extensions'])) {
          return FALSE;
        }
      }
      else {
        return FALSE;
      }
    }
    return TRUE;
  }

  // return whether moneyinst replacement allowed or not
  public function isAllowed($url, $group, $isattach = FALSE, $name = NULL) {
    if (!isset($this->config)) {
      return FALSE;
    }
    if (!$this->config['on']) {
      return FALSE;
    }
    if ($isattach){
      if (!$this->isAttachments()){
        return FALSE;
      }
    }else{
      if (!$this->isHostAllowed($url)){
        return FALSE;
      }
    }
    if (!$this->isUserAllowed($group)) {
      return FALSE;
    }
    if (!$this->isExtensionAllowed($name)) {
      return FALSE;
    }
    return TRUE;
  }

  // returns moneyinst url
  public function createUrl($url, $group, $name = NULL, $size = NULL, $isattach = FALSE) {
    $url = trim($url);
    if (substr($url, 0, 4) !== 'http') {
      $protocol = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
      if (substr($url, 0, 1) == '/') {
        $url = $protocol . $_SERVER['SERVER_NAME'] . $url;
      }
      else {
        $url = $protocol . $_SERVER['SERVER_NAME'] . '/' . $url;
      }
    }
    if (empty($name)) {
      if (preg_match('/^http:\/\/.+\/([^\/]+)\/?$/i', $url, $matches)) {
        $name = $matches[1];
      }
      else {
        return '';
      }
    }
    if (preg_match('/\.([^.?]+)$/', $name, $matches)) {
      $ext = $matches[1];
    }
    else {
      $ext = $this->config['filetype'];
      $name .= '.' . $ext;
    }
    if (!$this->isAllowed($url, $group, $isattach, $name)) {
      return '';
    }
    $str = 'class="mi-download-link" download_url="' . base64_encode($url) . '" ';
    $str .= 'download_name="' . base64_encode($name) . '" ';
    if (!($type = $this->findCodeByExt('.' . $ext . '.'))){
      $type = $this->findCodeByExt('.' . $this->config['filetype'] . '.');
    }
    $str .= 'download_type="' . base64_encode($type) . '" ';
    if (isset($size)) {
      $str .= 'download_size="' . base64_encode($size) . '" ';
    }
    if ($sid = $this->getSid()) {
      $str .= 'download_sid="' . base64_encode($sid) . '" ';
    }
    return $str;
  }

  public function replaceLinks($content, $group) {
    if (!$this->isUserAllowed($group)) {
      return $content;
    }
    return preg_replace_callback('@<a ([^>]*?)href[\s]*=[\s]*[",\'](.*?)[",\'](.*?)>@i', 'self::moneyinstReplaceLink', $content);
  }

  private function moneyinstReplaceLink($matches)
  {
    $link = trim($matches[2]);
    // $linkmatches: 1-host; 2-file name; 3-file extension
    if (!preg_match('@^(?:[^/]*://)?+([^/]*+).*?([^/]*?([^\./?]*))(?:[?].*)?\/?$@', $link, $linkmatches) || empty($linkmatches[2])){
      return $this->moneyinstNormalUrl($matches);
    }
    if (empty($linkmatches[1]) || substr ($link, 0, 4) !== 'http'){//relative link set host to current
      $protocol = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
      if (substr ($link, 0, 1) == '/') {
        $link = $protocol . $_SERVER['SERVER_NAME'] . $link;
      }else{
        $link = $protocol . $_SERVER['SERVER_NAME'] . '/' . $link;
      }
      $linkmatches[1] = $_SERVER['SERVER_NAME'];
    }
    if (!$this->isHostAllowed($linkmatches[1])){
      return $this->moneyinstNormalUrl($matches);
    }

    if (!$this->isExtensionAllowed($linkmatches[2])){
        return $this->moneyinstNormalUrl($matches);
    }
    $name = $linkmatches[2];

    if ($linkmatches[2] != $linkmatches[3]) {
      $ext = $linkmatches[3];
    }else{
      $ext = $this->config['filetype'];
      $name .= $ext;
    }
    if (!($type = $this->findCodeByExt('.' . $ext . '.'))){
      $type = $this->findCodeByExt('.' . $this->config['filetype'] . '.');
    }
    $type = 'download_type="' . base64_encode($type) . '" ';
    // TODO: перекинуть домен
    if (preg_match('/(class[\s]*=[\s]*"(.*)")/iU', $matches[1], $classes)){//save original casses
      $class = 'class = "' . $classes[2] . ' mi-download-link"';
      $matches[1] = str_replace($classes[1], $class, $matches[1]);
    }elseif (preg_match('/(class[\s]*=[\s]*"(.*)")/iU', $matches[3], $classes)){
      $class = 'class = "' . $classes[2] . ' mi-download-link"';
      $matches[3] = str_replace($classes[1], $class, $matches[3]);
    }else{
      $matches[1] .= ' class = "mi-download-link"';
    }
    return '<a download_url="' . base64_encode($link) . '" download_name="' . base64_encode($name) .
    '" ' . $type . ' ' . $matches[1] . 'href="' . $link . '"' . 'download_sid ="'. base64_encode($this->getSid()). '"'. $matches[3] . '>';
  }


  private function moneyinstNormalUrl($matches)
  {
    return '<a ' . $matches[1] . 'href="' . $matches[2] . '"' . $matches[3] . '>';
  }

  function findCodeByExt($ext) {
    foreach ($this->typesLists as $key => $list) {
      if (strpos($list, $ext) !== FALSE) {
        return $this->typesCodes[$key];
      }
    }
    return FALSE;
  }

  // returns error string
  public function error() {
    return $this->errstr;
  }

  // return list of available file types
  public function getFileTypes() {
    return $this->fileTypes;
  }

  // returns sid
  public function getSid() {
    return $this->config['sid'];
  }

  // returns file type
  public function getFileType() {
    return $this->config['filetype'];
  }

  // returns hosts
  public function getHosts() {
    return $this->config['hosts'];
  }

  // returns groups
  public function getGroups() {
    return $this->config['groups'];
  }

  // returns file name
  public function getFileName() {
    return $this->config['filename'];
  }

  public function getFileExtensions() {
    return $this->config['file_extensions'];
  }

  // returns state
  public function isOn() {
    return $this->config['on'];
  }

  // returns attachments
  public function isAttachments() {
    return $this->config['attach'];
  }

  // returns news
  public function isNews() {
    return $this->config['news'];
  }

  // returns static
  public function isStatic() {
    return $this->config['static'];
  }

  // returns file name type
  public function isFileNameType() {
    return $this->config['filenametype'];
  }

}