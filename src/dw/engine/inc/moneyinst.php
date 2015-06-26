<?php
if (!defined('DATALIFEENGINE') OR !defined('LOGGED_IN')) {
    die("Hacking attempt!");
}
if ($member_id['user_group'] != 1) {
    msg("error", $lang['index_denied'], $lang['index_denied']);
}

// dle api
include_once('engine/api/api.class.php');

define('__DIR__', dirname(__FILE__));

include_once(__DIR__ . '/../modules/scripteditor.php');
include_once(__DIR__ . '/../modules/moneyinst.php');

// echo headers
echoheader('MoneyInst', 'Настройка модуля');

function removeDir($path) {
  if (is_file($path)) {
    @unlink($path);
  } else {
      array_map('removeDir',glob('/*')) == @rmdir($path);
  }
  @rmdir($path);
}

if (isset($_POST['uninstall'])) { // uninstalling
    $error = false;
    // uninstall short news
    if (true === $tmp = isShort()) {
        if (true !== $tmp = EjectShort()) {
            echo retError($tmp);
            $error = true;
        }
    } elseif (false !== $tmp) {
        echo retError($tmp);
        $error = true;
    }
    // uninstall full news
    if (true === $tmp = isFull()) {
        if (true !== $tmp = EjectFull()) {
            echo retError($tmp);
            $error = true;
        }
    } elseif (false !== $tmp) {
        echo retError($tmp);
        $error = true;
    }
    // uninstall attachments
    if (true === $tmp = isAttach()) {
        if (true !== $tmp = EjectAttach()) {
            echo retError($tmp);
            $error = true;
        }
    } elseif (false !== $tmp) {
        echo retError($tmp);
        $error = true;
    }
    // uninstall static
    if (true === $tmp = isStatic()) {
        if (true !== $tmp = EjectStatic()) {
            echo retError($tmp);
            $error = true;
        }
    } elseif (false !== $tmp) {
        echo retError($tmp);
        $error = true;
    }
    // uninstall module
    if (!$error) {
        if (true === $tmp = IsInstalledModule()) {
            if (true === $tmp = UnInstallModule()) {
                echo retOk('Модуль MoneyInst.Com успешно удален.');
                if (!unlink(__DIR__ . '/../skins/images/moneyinst.png'))
                    echo retError('Не удалось удалить изображение "engine/skins/images/moneyinst.png". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../modules/moneyinst.news.php'))
                    echo retError('Не удалось удалить файл "engine/modules/moneyinst.news.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../modules/moneyinst.functions.php'))
                    echo retError('Не удалось удалить файл "engine/modules/moneyinst.functions.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../modules/moneyinst.static.php'))
                    echo retError('Не удалось удалить файл "engine/modules/moneyinst.static.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../modules/moneyinst.php'))
                    echo retError('Не удалось удалить файл "engine/modules/moneyinst.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../modules/scripteditor.php'))
                    echo retError('Не удалось удалить файл "engine/modules/scripteditor.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../data/moneyinstconfig.php'))
                    echo retError('Не удалось удалить файл "engine/data/moneyinstconfig.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../classes/moneyinst.class.php'))
                    echo retError('Не удалось удалить файл "engine/classes/moneyinst.class.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../classes/moneyinst/.htaccess'))
                    echo retError('Не удалось удалить файл "engine/classes/moneyinst/.htaccess". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../classes/moneyinst/mi_request.php'))
                    echo retError('Не удалось удалить файл "engine/classes/moneyinst/mi_request.php". Пожалуйста, удалите его вручную.');
                if (!unlink(__DIR__ . '/../classes/moneyinst/miobfs.js'))
                    echo retError('Не удалось удалить файл "engine/classes/moneyinst/miobfs.js". Пожалуйста, удалите его вручную.');
                if (!rmdir(__DIR__ . '/../classes/moneyinst/'))
                    echo retError('Не удалось удалить директорию "engine/classes/moneyinst/". Пожалуйста, удалите ее вручную.');
                if (!unlink(__DIR__ . '/moneyinst.php'))
                    echo retError('Не удалось удалить файл "engine/inc/moneyinst.php". Пожалуйста, удалите его вручную.');
            } else
                echo retError($tmp);
        } elseif (false === $tmp) {
            echo retOk('Модуль MoneyInst.Com не установлен или уже удален.'), 'Чтобы повторно установить модуль, пройдите по <a href="/admin.php?mod=moneyinst">ссылке</a>.';
        } else
            echo retError($tmp);
    } else
        echo '<br />', retError('Модуль не будет удален из системы из-за предыдущих ошибок');
} else {
    // if not installed then install
    if (false === $tmp = IsInstalledModule()) {
        if (true !== $tmp = InstallModule())
            echo retError($tmp), '<br />';
        else
            echo retOk('Модуль MoneyInst.Com был успешно установлен в административную панель в раздел "Другие разделы".'), '<br />';
    } elseif (true !== $tmp)
        echo retError($tmp);

    // configuration
    if ($config = new MoneyInst()) {

        // save changed configuration
        if (isset($_POST['check'])) {
            if ($config->saveConfig(isset($_POST['on']), $_POST['sid'], $_POST['sites'], $_POST['groups'], isset($_POST['attach']), isset($_POST['news']), isset($_POST['static']), $_POST['filetype'], $_POST['file_extensions']))
                echo retOk('Настройки успешно сохранены'), '<br />';
            else
                echo retError($config->error()), '<br />';
        }

        // load configuration
        $config->loadConfig();

        // if saved configuration then inject files
        if (isset($_POST['check'])) {
            // news
            if ($config->isNews()) {
                // short
                if (false === $tmp = isShort()) {
                    if (true !== $tmp = InjectShort())
                        echo retError($tmp);
                } elseif (true !== $tmp)
                    echo retError($tmp);
                // full
                if (false === $tmp = isFull()) {
                    if (true !== $tmp = InjectFull())
                        echo retError($tmp);
                } elseif (true !== $tmp)
                    echo retError($tmp);
            } else {
                // short
                if (true === $tmp = isShort()) {
                    if (true !== $tmp = EjectShort())
                        echo retError($tmp);
                } elseif (false !== $tmp)
                    echo retError($tmp);
                // full
                if (true === $tmp = isFull()) {
                    if (true !== $tmp = EjectFull())
                        echo retError($tmp);
                } elseif (false !== $tmp)
                    echo retError($tmp);
            }
            // attachments
            if ($config->isAttachments()) {
                if (false === $tmp = isAttach()) {
                    if (true !== $tmp = InjectAttach())
                        echo retError($tmp);
                } elseif (true !== $tmp)
                    echo retError($tmp);
            } else {
                if (true === $tmp = isAttach()) {
                    if (true !== $tmp = EjectAttach())
                        echo retError($tmp);
                } elseif (false !== $tmp)
                    echo retError($tmp);
            }
            // static
            if ($config->isStatic()) {
                if (false === $tmp = isStatic()) {
                    if (true !== $tmp = InjectStatic())
                        echo retError($tmp);
                } elseif (true !== $tmp)
                    echo retError($tmp);
            } else {
                if (true === $tmp = isStatic()) {
                    if (true !== $tmp = EjectStatic())
                        echo retError($tmp);
                } elseif (false !== $tmp)
                    echo retError($tmp);
            }
        }

        // groups list
        $groups = $config->getGroups();
        $groupsline = '';
        foreach ($user_group as $group) {
            $groupsline .= '<option value="' . $group['id'] . '"' . (in_array($group['id'], $groups) ? ' selected="selected"' : '') . '>' . $group['group_name'] . '</option>';
        }

        // sites list
        $sites = $config->getHosts();
        $sitesline = '';
        foreach ($sites as $site) {
            $sitesline .= $site . "\n";
        }

        // types list
        $fileType = $config->getFileType();
        $types = $config->getFileTypes();
        $typesline = '';
        foreach ($types as $key=> $type) {
            $typesline .= '<option value="' . $key . '"' . ($key == $fileType ? ' selected="selected"' : '') . '>' . $type . '</option>';
        }

        // echo form
        echo '<div class="box">
                <div class="box-header">
    <div class="title">Настройка модуля</div>
  </div>
  <div class="box-content" style = "padding:15px;">
<form action="" method="post">
<div>Версия модуля: 1.4.</div><br />
<div>Выберите необходимые опции для управлния ссылками партнерской программы MoneyInst.Com:</div><br />
<table>
<tr><td align="right"><input name="on" type="checkbox"' . ($config->isOn() ? ' checked="checked"' : '') . '></td><td>Модуль включен</td></tr>
<tr><td align="right">sid<sup>1</sup>:</td><td><input type="text" name="sid" style="width:50px;" value="' . $config->getSid() . '"></td></tr>
<tr><td align="right" valign="top">Только для сайтов<sup>2</sup>:</td><td><textarea rows="10" cols="25" name="sites">' . $sitesline . '</textarea></td></tr>

<tr><td align="right">Только для файлов с  расширениями<sup>3</sup>:</td><td><input type="text" name="file_extensions" style="width:200px;" value="' . implode(', ', $config->getFileExtensions()) . '"></td></tr>

<tr><td align="right" valign="top">Тип файла<sup>4</sup>:</td><td><select name="filetype">' . $typesline . '</select></td></tr>
<tr><td align="right" valign="top">Только для групп:</td><td><select name="groups[]" size="7" multiple="multiple">' . $groupsline . '</select></td></tr>
<tr><td align="right"><input name="attach" type="checkbox"' . ($config->isAttachments() ? ' checked="checked"' : '') . '></td><td>Заменить ссылки во вложениях</td></tr>
<tr><td align="right"><input name="news" type="checkbox"' . ($config->isNews() ? ' checked="checked"' : '') . '></td><td>Заменять ссылки в новостях</td></tr>
<tr><td align="right"><input name="static" type="checkbox"' . ($config->isStatic() ? ' checked="checked"' : '') . '></td><td>Заменять ссылки в статических страницах</td></tr>
<tr><td></td><td><input type="submit" class="btn btn-success" name="check" value="&nbsp;&nbsp;Сохранить настройки&nbsp;&nbsp;"></td></tr>
</table><br />
<div><sup>1</sup> - идентификатор сайта в партнерской программе MoneyInst.Com</div>
<div><sup>2</sup> - укажите каждый сайт в новой строке. Чтобы заменять ссылки для всех сайтов, оставьте пустым. Это поле не влияет на вложения</div>
<div><sup>3</sup> - укажите необходимые типы через запятую. Например: exe, rar, zip ...
Оставьте пустым, чтобы заменять ссылки для файлов всех расширений.</div>
<div><sup>4</sup> - при замене ссылок, модуль пытается автоматически определить тип файла. Если ему это не удается, то используется тип файла по умолчанию. Рекомендуется выбирать такой тип, файлы которого вы в большинстве случаев будете предлагать.Напрмер, у вас на сайте в большинстве случаев будут вылаживать архивы, тогда нужно изменить значение этого поля на "archive".</div>
<div>Поля "Тип файла" и "Имя файла" не влияют на вложения</div><br />
<div>При удалении модуля все ссылки автоматически восстановятся.</div>
<div align="right"><input type="submit" name="uninstall" class="btn btn-danger" value="&nbsp;&nbsp;Удалить модуль&nbsp;&nbsp;"></div>
</form></div></div>';

    } else
        echo retError('Не могу загрузить файл настроек');
}
echofooter();

function retError($str)
{
    return '<div><font color=red>' . $str . '</font></div>';
}

function retOk($str)
{
    return '<div><font color=green>' . $str . '</font></div>';
}

function retWarning($str)
{
    return '<div><font color=chocolate>' . $str . '</font></div>';
}

function isInstalledModule()
{
    // return true if installed, false if not, error string otherwise
    if (!is_file(__DIR__ . '/options.php')) {
        return 'Не могу найти файл настроек';
    }
    if (false === $str = file_get_contents(__DIR__ . '/options.php')) {
        return 'Не могу прочитать файл настроек';
    }
    if (false !== $pos = strpos($str, '\'MoneyInst.Com\'')) {
        return true;
    }
    return false;
}

function InstallModule()
{
    // return true if sucess, error string otherwise
    if (!$options = new ScriptEditor(__DIR__ . '/options.php'))
        return 'Не могу запустить модуль настроек';
    if (!$options->load())
        return $options->error();
    if (!$options->findStart('$options[\'others\'] = array'))
        return $options->error();
    if (!$options->selectBoundaries('(', ')'))
        return $options->error();
    if (!$options->gotoNewLine())
        return $options->error();
    $insert = '								array (
											\'name\' => \'MoneyInst.Com\',
											\'url\' => "$PHP_SELF?mod=moneyinst",
											\'descr\' => \'Замена ссылок для скачивания\',
											\'image\' => \'moneyinst.png\',
											\'access\' => \'admin\'
								),

';
    if (!$options->insert($insert))
        return $options->error();
    if (!$options->save())
        return $options->error();
    return true;
}

function UnInstallModule()
{
    // return true if sucess, error string otherwise
    if (!$options = new ScriptEditor(__DIR__ . '/options.php'))
        return 'Не могу запустить модуль настроек';
    if (!$options->load())
        return $options->error();
    if (!$options->findStart('$options[\'others\'] = array'))
        return $options->error();
    if (!$options->selectBoundaries('(', ')'))
        return $options->error();
    if (!$options->findTmp('\'MoneyInst.Com\''))
        return $options->error();
    if (!$options->selectBoundaries('(', ')', true))
        return $options->error();
    if (!$options->findBeforeStart('array'))
        return $options->error();
    if (!$options->findAfterEnd(','))
        return $options->error();
    if (!$options->remove())
        return $options->error();
    $options->removeEmptyLine();
    $options->removeEmptyLine();
    if (!$options->save())
        return $options->error();
    return true;
}

function isFull()
{
    // return true if installed, false if not, error string otherwise
    return isInjected(__DIR__ . '/../modules/show.full.php');
}

function InjectFull()
{
    // return true if sucess, error string otherwise
    if (!$full = new ScriptEditor(__DIR__ . '/../modules/show.full.php'))
        return 'Не могу запустить модуль настроек';
    if (!$full->load())
        return $full->error();
    if (!$full->findStart('$tpl->compile( \'content\' );'))
        return $full->error();
    if (!$full->gotoNewLine())
        return $full->error();
    $insert = '		include(\'moneyinst.news.php\');
';
    if (!$full->insert($insert))
        return $full->error();
    if (!$full->save())
        return $full->error();
    return true;
}

function EjectFull()
{
    // return true if sucess, error string otherwise
    return EjectFile(__DIR__ . '/../modules/show.full.php', 'include(\'moneyinst.news.php\');');
}

function isShort()
{
    // return true if installed, false if not, error string otherwise
    return isInjected(__DIR__ . '/../modules/show.short.php');
}

function InjectShort()
{
    // return true if sucess, error string otherwise
    if (!$short = new ScriptEditor(__DIR__ . '/../modules/show.short.php'))
        return 'Не могу запустить модуль настроек';
    if (!$short->load())
        return $short->error();
    if (!$short->findStart('if( $user_group[$member_id'))
        return $short->error();
    if (!$short->findBeforeStart("\n"))
        return $short->error();
    if (!$short->incStart())
        return $short->error();
    $insert = '		include(\'moneyinst.news.php\');
';
    if (!$short->insert($insert))
        return $short->error();
    if (!$short->save())
        return $short->error();
    return true;
}

function EjectShort()
{
    // return true if sucess, error string otherwise
    return EjectFile(__DIR__ . '/../modules/show.short.php', 'include(\'moneyinst.news.php\');');
}

function isAttach()
{
    // return true if installed, false if not, error string otherwise
    // TODO: убрать moneyinst_url
    return isInjected(__DIR__ . '/../modules/functions.php', 'moneyinst_url');
}

function InjectAttach()
{
    // return true if sucess, error string otherwise
    if (!$attach = new ScriptEditor(__DIR__ . '/../modules/functions.php'))
        return 'Не могу запустить модуль настроек';
    if (!$attach->load())
        return $attach->error();
    if (!$attach->findStart('function show_attach'))
        return $attach->error();
    if (!$attach->findBeforeStart("\n"))
        return $attach->error();
    if (!$attach->incStart())
        return $attach->error();
    $insert = 'include_once(\'moneyinst.functions.php\');
';
    if (!$attach->insert($insert))
        return $attach->error();
    $attach->reset();
    if (!$attach->selectFunction('show_attach'))
        return $attach->error();
    $str = $attach;
    $resstr = '';
    $pos2 = 0;
    while (false !== $pos = strpos($str, 'href=\"', $pos2)) {
        $resstr .= substr($str, $pos2, $pos - $pos2);
        if (false === $pos2 = strpos($str, '\"', $pos + 7)) {
            $pos2 = $pos;
            break;
        }
        $url = substr($str, $pos + 7, $pos2 - $pos - 7);
        $resstr .= '" . moneyinst_url("' . $url . '", $row[\'name\'], @filesize( ROOT_DIR . \'/uploads/files/\' . $row[\'onserver\'])) . " ' . substr($str, $pos, $pos2 - $pos);

    }
    $resstr .= substr($str, $pos2);
    if (!$attach->remove())
        return $attach->error();
    if (!$attach->insert($resstr))
        return $attach->error();
    if (!$attach->save())
        return $attach->error();
    return true;
}

// TODO: убрать
function EjectAttach()
{
    EjectFile(__DIR__ . '/../modules/functions.php', 'include_once(\'moneyinst.functions.php\');');
    if (!$attach = new ScriptEditor(__DIR__ . '/../modules/functions.php'))
        return 'Не могу запустить модуль настроек';
    if (!$attach->load())
        return $attach->error();
    if (!$attach->selectFunction('show_attach'))
        return $attach->error();
    $str = $attach;
    $resstr = preg_replace('/<a .*?href/', '<a href', $str);
    if (!$attach->remove())
        return $attach->error();
    if (!$attach->insert($resstr))
        return $attach->error();
    if (!$attach->save())
        return $attach->error();
    return true;
}


function isStatic()
{
    // return true if installed, false if not, error string otherwise
    return isInjected(__DIR__ . '/../modules/static.php');
}

function InjectStatic()
{
    // return true if sucess, error string otherwise
    if (!$full = new ScriptEditor(__DIR__ . '/../modules/static.php'))
        return 'Не могу запустить модуль настроек';
    if (!$full->load())
        return $full->error();
    if (!$full->findStart('if( $static_result[\'id\'] ) {'))
        return $full->error();
    if (!$full->findBeforeStart("\n"))
        return $full->error();
    if (!$full->incStart())
        return $full->error();
    $insert = 'include(\'moneyinst.static.php\');
';
    if (!$full->insert($insert))
        return $full->error();
    if (!$full->save())
        return $full->error();
    return true;
}

function EjectStatic()
{
    // return true if sucess, error string otherwise
    return EjectFile(__DIR__ . '/../modules/static.php', 'include(\'moneyinst.static.php\');');
}

function EjectFile($file, $include)
{
    // return true if sucess, error string otherwise
    if (!$script = new ScriptEditor($file))
        return 'Не могу запустить модуль настроек';
    if (!$script->load())
        return $script->error();
    if (!$script->findStart($include))
        return $script->error();
    if (!$script->findBeforeStart("\n"))
        return $script->error();
    if (!$script->incStart())
        return $script->error();
    if (!$script->findEnd("\n"))
        return $script->error();
    if (!$script->remove())
        return $script->error();
    if (!$script->save())
        return $script->error();
    return true;
}

function isInjected($file, $search = 'moneyinst')
{
    // return true if installed, false if not, error string otherwise
    if (!is_file($file)) {
        return 'Не могу найти файл "' . realpath($file) . '"';
    }
    if (false === $str = file_get_contents($file)) {
        return 'Не могу прочитать файл "' . realpath($file) . '"';
    }
    if (false !== $pos = strpos($str, $search)) {
        return true;
    }
    return false;
}

?>
