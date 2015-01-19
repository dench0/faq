<?php
/*
Plugin Name: MoneyInst.com
Plugin URI: http://moneyinst.com/
Description: Модуль замены ссылок партнерской программы MoneyInst.com
Author: MoneyInst.com
Version: 2.0.0
Author URI: http://moneyinst.com
*/

$moneyinstFileTypes = array(
  'exe' => 'Не указывать',
  'mp3' => 'audio',
  'avi' => 'video',
  'exe' => 'setup',
  'pdf' => 'book',
  'torrent' => 'torrent',
  'rar' => 'archive',
  'iso' => 'disk',
  'jpg' => 'image');

$typesLists = array('archive' => '.7z.bz2.cab.deb.jar.rar.rpm.tar.zip.',
                   'video'   => ".3gp.aaf.asf.flv.mkv.mov.mpeg.qt.wmv.hdv.mpeg4.mp4.dvd.mxf.avi.",
                   'audio'   => ".aac.asf.cda.fla.mp3.ogg.wav.wma.cd.ac3.dts.flac.midi.mod.aud.",
                   'image'   => ".bmp.cpt.gif.jpeg.jpg.jp2.pcx.png.psd.tga.tpic.tiff.tif.wdp.hdp.cdr.svg.ico.ani.cur.xcf.",
                   'torrent' => ".torrent.",
                   'android' => ".apk.",
                   'book'    => ".ps.eps.pdf.doc.txt.rtf.djvu.opf.chm.sgml.xml.fb2.fb3.tex.lit.exebook.prc.epub.",
                   'disk'    => ".img.iso.nrg.mdf.uif.bin.cue.daa.pqi.cso.ccd.sub.wim.swm.rwm.");

$typesCodes = array('archive' => 1,
                   'video'   => 3,
                   'audio'   => 4,
                   'image'   => 5,
                   'torrent' => 6,
                   'android' => 7,
                   'book'    => 8,
                   'disk'    => 9,);

$moneyinstOptDefaultSid = 0;
$moneyinstOptDefaultSites = array();
$moneyinstOptDefaultExt = array();
$moneyinstOptDefaultFileType = 0;

$moneyinstOptSid = $moneyinstOptDefaultSid;
$moneyinstOptSites = $moneyinstOptDefaultSites;
$moneyinstOptExt = $moneyinstOptDefaultExt;
$moneyinstOptFileType = $moneyinstOptDefaultFileType;

add_action('init', 'moneyinstInit');
add_action('admin_menu', 'moneyinstSettings');
add_filter('the_content', 'moneyinstReplace');

wp_enqueue_script('jquery');
wp_enqueue_script('moneyinst_js', plugins_url('moneyinst/mi-clear.js'));//plugins_url('moneyinst/miobfs.js'));

function moneyinstInit()
{
    moneyinstLoadOptions();
}

function moneyinstLoadOptions()
{
    global $moneyinstFileTypes;
    global $moneyinstOptDefaultDomain;
    global $moneyinstOptDefaultSid;
    global $moneyinstOptDefaultSites;
    global $moneyinstOptDefaultExt;
    global $moneyinstOptDefaultFileType;


    global $moneyinstOptSid;
    global $moneyinstOptSites;
    global $moneyinstOptExt;
    global $moneyinstOptFileType;


    $moneyinstOptSid = get_option('moneyinst_sid');
    $moneyinstOptSites = get_option('moneyinst_sites');
    $moneyinstOptExt = get_option('moneyinst_ext');
    $moneyinstOptFileType = get_option('moneyinst_filetype');


    if ($moneyinstOptSid === false) $moneyinstOptSid = $moneyinstOptDefaultSid;
    if ($moneyinstOptSites === false) $moneyinstOptSites = $moneyinstOptDefaultSites;
    if ($moneyinstOptExt === false) $moneyinstOptExt = $moneyinstOptDefaultExt;
    if ($moneyinstOptFileType === false) $moneyinstOptFileType = $moneyinstOptDefaultFileType;


    if ($moneyinstOptFileType < 0 or $moneyinstOptFileType >= count($moneyinstFileTypes))
        $moneyinstOptFileType = 0;
}

function moneyinstSettings()
{
    add_options_page('Настройки MoneyInst.com', 'MoneyInst.com', 8, 'moneyinst', 'moneyinstSettingsPage');
}

function moneyinstSettingsPage()
{
    global $moneyinstFileTypes;
    global $moneyinstOptSid;
    global $moneyinstOptSites;
    global $moneyinstOptExt;
    global $moneyinstOptFileType;


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Настройки сохранены.</strong></p></div>';
        update_option('moneyinst_sid', intval($_POST['sid']));
        $tmpSites = explode("\n", $_POST['sites']);
        $sites = array();
        foreach ($tmpSites as $site)
            if ($tmpSite = trim($site))
                $sites[] = $tmpSite;
        $sites = array_unique($sites);
        update_option('moneyinst_sites', $sites);
        $tmpExt = explode(",", $_POST['ext']);
        $exts = array();
        foreach ($tmpExt as $ext)
            if ($tmpExt = trim($ext))
                $exts[] = $tmpExt;
        $exts = array_unique($exts);
        update_option('moneyinst_ext', $exts);
        update_option('moneyinst_filetype', intval($_POST['filetype']));
        moneyinstLoadOptions();
    }

    $typesline = '';
    foreach ($moneyinstFileTypes as $key=> $type) {
        $typesline .= '<option value="' . $key . '"' . ($key == $moneyinstOptFileType ? ' selected="selected"' : '') . '>' . $type . '</option>';
    }
    ?>
<div id="wpbody-content">
    <div class="wrap">
        <div id="icon-options-general" class="icon32"><br></div>
        <h2>Настройки MoneyInst.com</h2>

        <form method="POST" action="">
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row"><label for="sid">Партнерский идентификатор</label></th>
                    <td><input name="sid" type="text" id="sid" value="<?php echo $moneyinstOptSid; ?>"
                               class="regular-text"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="sites">Только для сайтов</label></th>
                    <td>
                        <textarea name="sites" rows="5" cols="40"
                                  id="sites"><?php echo implode("\n", $moneyinstOptSites); ?></textarea>

                        <p class="description">Укажите каждый сайт в новой строке. Чтобы заменять ссылки для всех сайтов, оставьте пустым</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="ext">Только для расширений</label></th>
                    <td><input name="ext" type="text" id="ext" value="<?php echo implode(', ', $moneyinstOptExt); ?>"
                               class="regular-text">

                        <p class="description">Заменяет ссылки только для файлов с указанными расширениями. Перечислите расширения через запятую, например: "exe, rar, zip". Оставьте пустым, чтобы заменять ссылки для файлов всех расширений.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="filetype">Тип файла по умолчанию</label></th>
                    <td>
                        <select name="filetype" id="filetype"><?php echo $typesline; ?></select>
                        <p class="description">Будет использоваться если в ссылке не возможно определить тип файла</p>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="submit" name="submit" id="submit" class="button-primary" value="Сохранить изменения"/>
        </form>
    </div>
</div>
<?php
}

function moneyinstReplace($content)
{
    return preg_replace_callback('@<a ([^>]*?)href[\s]*=[\s]*[",\'](.*?)[",\'](.*?)>@i', 'moneyinstReplaceLink', $content);
}

function moneyinstReplaceLink($matches)
{
    /*global $moneyinstFileTypes;
    global $moneyinstTypesExt;*/
    global $moneyinstOptSid;
    global $moneyinstOptSites;
    global $moneyinstOptExt;
    global $moneyinstOptFileType;

    $link = trim($matches[2]);
    // $linkmatches: 1-host; 2-file name; 3-file extension
    if (!preg_match('@^(?:[^/]*://)?+([^/]*+).*?([^/]*?([^\./]*))\/?$@', $link, $linkmatches) || empty($linkmatches[2])){
      return moneyinstNormalUrl($matches);
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
    if (count($moneyinstOptSites)) {//проверка на допустимы хосt
      $foundSite = false;
      foreach ($moneyinstOptSites as $site) {
        if (substr($linkmatches[1], -strlen($site)) == $site) {
          $foundSite = true;
          break;
        }
      }
      if (!$foundSite){
        return moneyinstNormalUrl($matches);
      }
    }
    if (count($moneyinstOptExt) && isset($linkmatches[3])) {//проверка на допустимое расширение
      $foundExt = false;
      foreach ($moneyinstOptExt as $ext) {
        if ($linkmatches[3] == $ext) {
          $foundExt = true;
          break;
        }
      }
      if (!$foundExt){
        return moneyinstNormalUrl($matches);
      }
    }
    $name = $linkmatches[2];

    if ($linkmatches[2] != $linkmatches[3]) {
      $ext = $linkmatches[3];
    }else{
      $ext = $moneyinstOptFileType;
    }
    if (!($type = findCodeByExt('.' . $ext . '.'))){
      $type = findCodeByExt('.' . $moneyinstOptFileType . '.');
    }
    $type = 'download_type="' . base64_encode($type) . '" ';
    // TODO: перекинуть домен
    if (preg_match('/(class[\s]*=[\s]*"(.*)")/iU', $matches[1], $classes)){//save original casses
        $class = 'class="' . $classes[2] . ' mi-download-link"';
        $matches[1] = str_replace($classes[1], $class, $matches[1]);
    }elseif (preg_match('/(class[\s]*=[\s]*"(.*)")/iU', $matches[3], $classes)){
        $class = 'class="' . $classes[2] . ' mi-download-link"';
        $matches[3] = str_replace($classes[1], $class, $matches[3]);
    }else{
      $matches[1] .= ' class="mi-download-link"';
    }
    return '<a download_url="' . base64_encode($link) . '" download_name="' . base64_encode($name) .
    '" ' . $type . ' ' . $matches[1] . 'href="' . $link . '"' . ' download_sid ="'. base64_encode($moneyinstOptSid). '"'. $matches[3] . '>';
}

function findCodeByExt($ext){
  global $typesLists;
  global $typesCodes;
  foreach($typesLists as $key => $list){
    if (strpos($list, $ext) !== false){
      return $typesCodes[$key];
    }
  }
  return 2;
}

function moneyinstNormalUrl($matches)
{
    return '<a ' . $matches[1] . 'href="' . $matches[2] . '"' . $matches[3] . '>';
}
