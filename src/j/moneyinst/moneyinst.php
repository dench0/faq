<?php

// no direct access
defined('_JEXEC') or die;

class plgSystemMoneyinst extends JPlugin{
  protected $autoloadLanguage = true;
	private $moneyinstOptSites;
	private $moneyinstOptSid;
	private $moneyinstOptExt;
  private $moneyinstOptFileType;
  private $typesLists = array('archive' => '.7z.bz2.cab.deb.jar.rar.rpm.tar.zip.',
                   'video'   => ".3gp.aaf.asf.flv.mkv.mov.mpeg.qt.wmv.hdv.mpeg4.mp4.dvd.mxf.avi.",
                   'audio'   => ".aac.asf.cda.fla.mp3.ogg.wav.wma.cd.ac3.dts.flac.midi.mod.aud.",
                   'image'   => ".bmp.cpt.gif.jpeg.jpg.jp2.pcx.png.psd.tga.tpic.tiff.tif.wdp.hdp.cdr.svg.ico.ani.cur.xcf.",
                   'torrent' => ".torrent.",
                   'android' => ".apk.",
                   'book'    => ".ps.eps.pdf.doc.txt.rtf.djvu.opf.chm.sgml.xml.fb2.fb3.tex.lit.exebook.prc.epub.",
                   'disk'    => ".img.iso.nrg.mdf.uif.bin.cue.daa.pqi.cso.ccd.sub.wim.swm.rwm.");

	private $typesCodes = array('archive' => 1,
                   'video'   => 3,
                   'audio'   => 4,
                   'image'   => 5,
                   'torrent' => 6,
                   'android' => 7,
                   'book'    => 8,
                   'disk'    => 9,);

	public function __construct(& $subject, $config)
  {
    parent::__construct($subject, $config);
    $this->loadLanguage();
    $tmpSites = explode("\n", $this->params->get('sites'));
    $sites = array();
    foreach ($tmpSites as $site){
    	if ($tmpSite = trim($site)){
      	$sites[] = $tmpSite;
      }
    }
		$this->moneyinstOptSites = array_unique($sites);
		$this->moneyinstOptSid = $this->params->get('sid');
		$this->moneyinstOptFileType = $this->params->get('default_type');
	 	$tmpExt = explode(",", $this->params->get('extensions'));
    $exts = array();
    foreach ($tmpExt as $ext){
      if ($tmpExt = trim($ext)){
        $exts[] = $tmpExt;
    	}
  	}
    $this->moneyinstOptExt = array_unique($exts);
  }

 	public function onBeforeRender() {
    $app = JFactory::getApplication();
    if(!$app->isSite()){
      return;
    }
    $document = JFactory::getDocument();
    if ($document->getType() != 'html') {
      return;
    }
    JHtml::_('jquery.framework');
    $document->addScript(JURI::base(). "plugins/system/moneyinst/mi-clear.js");
    $content = $document->getBuffer('component');
    $content = $this->moneyinstReplace($content);
    $document->setBuffer($content, 'component');
    return true;
	}

	private function moneyinstReplace($content)
	{
    return preg_replace_callback('@<a ([^>]*?)href[\s]*=[\s]*[",\'](.*?)[",\'](.*?)>@i', 'self::moneyinstReplaceLink', $content);
	}

	private function moneyinstReplaceLink($matches)
	{
    $link = trim($matches[2]);
    // $linkmatches: 1-host; 2-file name; 3-file extension
    if (!preg_match('@^(?:[^/]*://)?+([^/]*+).*?([^/]*?([^\./]*))\/?$@', $link, $linkmatches) || empty($linkmatches[2])){
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
    if (count($this->moneyinstOptSites)) {//проверка на допустимы хосt
      $foundSite = false;
      foreach ($this->moneyinstOptSites as $site) {
        if (substr($linkmatches[1], -strlen($site)) == $site) {
          $foundSite = true;
          break;
        }
      }
      if (!$foundSite){
        return $this->moneyinstNormalUrl($matches);
      }
    }
    if (count($this->moneyinstOptExt) && isset($linkmatches[3])) {//проверка на допустимое расширение
      $foundExt = false;
      foreach ($this->moneyinstOptExt as $ext) {
        if ($linkmatches[3] == $ext) {
          $foundExt = true;
          break;
        }
      }
      if (!$foundExt){
        return $this->moneyinstNormalUrl($matches);
      }
    }
    $name = $linkmatches[2];;

    if ($linkmatches[2] != $linkmatches[3]) {
      $ext = $linkmatches[3];
    }else{
      $ext = $this->moneyinstOptFileType;
    }
    $type = 'download_type="' . base64_encode($this->findCodeByExt('.' . $ext . '.')) . '" ';
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
    '" ' . $type . ' ' . $matches[1] . 'href="' . $link . '"' . 'download_sid ="'. base64_encode($this->moneyinstOptSid). '"'. $matches[3] . '>';
	}

	private function findCodeByExt($ext){
	  foreach($this->typesLists as $key => $list){
	    if (strpos($list, $ext) !== false){
	      return $this->typesCodes[$key];
	    }
	  }
	  return 2;
	}

	private function moneyinstNormalUrl($matches)
	{
	    return '<a ' . $matches[1] . 'href="' . $matches[2] . '"' . $matches[3] . '>';
	}
}
?>
