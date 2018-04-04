<?php
/**
 * Jobs for XOOPS
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package     jobs
 * @author      John Mordo aka jlm69 (www.jlmzone.com )
 * @author      XOOPS Development Team
 */

// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //

use XoopsModules\Jobs;
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

include __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
$moduleDirName = basename(__DIR__);
$module_id     = $xoopsModule->getVar('mid');
if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$grouppermHandler = xoops_getHandler('groupperm');

if (isset($_POST['item_id'])) {
    $perm_itemid = (int)$_POST['item_id'];
} else {
    $perm_itemid = 0;
}
//If no access
if (!$grouppermHandler->checkRight('' . $moduleDirName . '_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}

//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";

if (!empty($_POST['submit'])) {
    // Define Variables for register_globals Off
    $id        = !isset($_REQUEST['id']) ? null : $_REQUEST['id'];
    $date      = !isset($_REQUEST['date']) ? null : $_REQUEST['date'];
    $namep     = !isset($_REQUEST['namep']) ? null : $_REQUEST['namep'];
    $ipnumber  = !isset($_REQUEST['ipnumber']) ? null : $_REQUEST['ipnumber'];
    $messtext  = !isset($_REQUEST['messtext']) ? null : $_REQUEST['messtext'];
    $typeprice = !isset($_REQUEST['typeprice']) ? null : $_REQUEST['typeprice'];
    $price     = !isset($_REQUEST['price']) ? null : $_REQUEST['price'];
    $private   = !isset($_REQUEST['private']) ? null : $_REQUEST['private'];
    $messtext  = !isset($_REQUEST['messtext']) ? null : $_REQUEST['messtext'];
    $tele      = !isset($_REQUEST['tele']) ? null : $_REQUEST['tele'];
    $post      = !isset($_REQUEST['post']) ? null : $_REQUEST['post'];
    $resume    = !isset($_REQUEST['resume']) ? null : $_REQUEST['resume'];
    // end define vars

    global $xoopsConfig, $xoopsDB, $myts, $meta, $moduleDirName, $private;

    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/viewjobs.php?lid=" . addslashes($id) . '', 3, $GLOBALS['xoopsSecurity']->getErrors());
    }
    //  if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
    //  $x24plus = jobs_isX24plus();
    //  if ($x24plus) {
    //  xoops_load("xoopscaptcha");
    //  $xoopsCaptcha = XoopsCaptcha::getInstance();
    //  if ( !$xoopsCaptcha->verify() ) {
    //        redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, $xoopsCaptcha->getMessage() );
    //  }
    //  } else {
    //  xoops_load("captcha");
    //  $xoopsCaptcha = XoopsCaptcha::getInstance();
    //  if ( !$xoopsCaptcha->verify() ) {
    //        redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, $xoopsCaptcha->getMessage() );
    //  }
    //  }
    //  }
    if (isset($_POST['private'])) {
        $private = (int)$_POST['private'];
    } else {
        $private = '';
    }
    if (isset($_POST['unlock'])) {
        $unlock = (int)$_POST['unlock'];
    } else {
        $unlock = '';
    }

    $result = $xoopsDB->query('SELECT lid, email, submitter, title, type, company, desctext, requirements, usid FROM  ' . $xoopsDB->prefix('jobs_listing') . ' WHERE lid = ' . $xoopsDB->escape($id) . '');

    while (false !== (list($lid, $email, $submitter, $title, $type, $company, $desctext, $requirements, $usid) = $xoopsDB->fetchRow($result))) {
        $comp_id     = jobs_getCompIdFromName(addslashes($company));
        $extra_users = jobs_getThisCompany($comp_id, $usid);

        if ($extra_users) {
            $extra_user1 = $extra_users['comp_user1'];
        } else {
            $extra_user1 = '';
        }
        if ($extra_users) {
            $extra_user2 = $extra_users['comp_user2'];
        } else {
            $extra_user2 = '';
        }
        if ($extra_user1) {
            $result3 = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uid=$extra_user1");
            list($extra_user1_email) = $xoopsDB->fetchRow($result3);
            $extra_user1_email = $extra_user1_email;
        } else {
            $extra_user1_email = '';
        }

        if ($extra_user2) {
            $result4 = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uid=$extra_user2");
            list($extra_user2_email) = $xoopsDB->fetchRow($result4);
            $extra_user2_email = $extra_user2_email;
        } else {
            $extra_user2_email = '';
        }

        $title   = $myts->addSlashes($title);
        $type    = $myts->addSlashes($type);
        $company = $myts->addSlashes($company);

        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $desctext = $myts->undoHtmlSpecialChars($myts->displayTarea($desctext, 0, 0, 1, 1, 0));
        } else {
            $desctext = $myts->displayTarea($desctext, 0, 0, 1, 1, 1);
        }

        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $requirements = $myts->displayTarea($requirements, 0, 0, 1, 1, 0);
        } else {
            $requirements = $myts->displayTarea($requirements, 1, 0, 1, 1, 1);
        }

        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $messtext = $myts->displayTarea($messtext, 0, 0, 1, 1, 0);
        } else {
            $messtext = $myts->displayTarea($messtext, 1, 0, 1, 1, 1);
        }

        $submitter = $myts->addSlashes($submitter);
        $date      = time();
        $r_usid    = $xoopsUser->getVar('uid', 'E');

        $tags                = [];
        $tags['TITLE']       = $title;
        $tags['TYPE']        = $type;
        $tags['COMPANY']     = $company;
        $tags['DESCTEXT']    = stripslashes($desctext);
        $tags['MY_SITENAME'] = $xoopsConfig['sitename'];
        $tags['REPLY_ON']    = _JOBS_REMINDANN;
        $tags['DESCRIPT']    = _JOBS_DESC;
        $tags['STARTMESS']   = _JOBS_STARTMESS;
        $tags['MESSFROM']    = _JOBS_MESSFROM;
        $tags['CANJOINT']    = _JOBS_CANJOINT;
        $tags['NAMEP']       = $_POST['namep'];
        $tags['TO']          = _JOBS_TO;
        $tags['POST']        = $_POST['post'];
        $tags['TELE']        = $tele;
        $tags['ENDMESS']     = _JOBS_ENDMESS;
        $tags['SECURE_SEND'] = _JOBS_SECURE_SEND;
        $tags['SUBMITTER']   = $submitter;
        $tags['MESSTEXT']    = stripslashes($messtext);
        $tags['EMAIL']       = _JOBS_EMAIL;
        $tags['TEL']         = _JOBS_TEL;
        $tags['HELLO']       = _JOBS_HELLO;
        $tags['REPLIED_BY']  = _JOBS_REPLIED_BY;
        $tags['YOUR_JOB']    = _JOBS_YOUR_JOB;
        $tags['THANKS']      = _JOBS_THANK;
        $tags['WEBMASTER']   = _JOBS_WEBMASTER;
        $tags['AT']          = _JOBS_AT;
        $tags['UNLOCK']      = $unlock;
        $tags['PRIVATE']     = $private;
        $tags['SENDER_IP']   = $_SERVER['REMOTE_ADDR'];
        $tags['VIEW_YOUR']   = _JOBS_VIEW_JOB;
        if (('0' == $resume) || ('' == $resume)) {
            $tags['VIEW_RESUME'] = '';
            $tags['RESUME_URL']  = '';
        } else {
            $tags['VIEW_RESUME'] = _JOBS_RES_VIEW;
            $tags['RESUME_URL']  = $resume;
        }
        $tags['RESUME_PRIVKEY'] = _JOBS_RES_PRIVKEY;
        $tags['LINK_URL']       = '' . XOOPS_URL . "/modules/$moduleDirName/viewjobs.php?&lid=" . addslashes($lid) . '';
        $subject                = '' . _JOBS_CONTACTAFTERANN . '';
        $mail                   = xoops_getMailer();
        if (is_dir('language/' . $xoopsConfig['language'] . '/mail_template/')) {
            $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $xoopsConfig['language'] . '/mail_template/');
        } else {
            $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/english/mail_template/");
        }
        $mail->setTemplate('jobs_listing_contact.tpl');
        $mail->useMail();
        $mail->setFromEmail($post);
        $mail->setToEmails([$email, $extra_user1_email, $extra_user2_email]);
        $mail->setSubject($subject);
        $mail->multimailer->isHTML(true);
        $mail->assign($tags);
        $mail->send();
        echo $mail->getErrors();

        if (1 == $helper->getConfig('jobs_admin_mail')) {
            $jsubject    = $xoopsConfig['sitename'] . ' Job Reply ';
            $xoopsMailer = xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
            $xoopsMailer->setFromName($xoopsConfig['sitename']);
            $xoopsMailer->setSubject($jsubject);
            $xoopsMailer->multimailer->isHTML(true);
            $xoopsMailer->assign($tags);
            $xoopsMailer->send();
            echo $xoopsMailer->getErrors();
        }

        $company = addslashes($company);
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('jobs_replies') . " values ('','$id', '$title', '$date', '$namep', '$messtext', '$resume', '$tele', '" . $_POST['post'] . "', '$r_usid', '$company')");
    }
    redirect_header('index.php', 3, _JOBS_MESSEND);
} else {
    $lid     = \Xmf\Request::getInt('lid', '', 'GET');
    $private = \Xmf\Request::getInt('private', '', 'GET');

    //If no access
    if (!$grouppermHandler->checkRight('' . $moduleDirName . '_view', $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    global $xoopsConfig, $xoopsDB, $myts, $meta, $moduleDirName;

    include XOOPS_ROOT_PATH . '/header.php';
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    $time     = time();
    $ipnumber = (string)$_SERVER[REMOTE_ADDR];
    echo '<script type="text/javascript">
          function verify()
          {
                var msg = "' . _JOBS_VALIDERORMSG . "\\n__________________________________________________\\n\\n\";
                var errors = \"false\";

                if (document.cont.namep.value == \"\") {
                        errors = \"true\";
                        msg += \"" . _JOBS_VALIDSUBMITTER . "\\n\";
                }
                if (document.cont.post.value == \"\") {
                        errors = \"true\";
                        msg += \"" . _JOBS_VALIDEMAIL . "\\n\";
                }
                if (document.cont.messtext.value == \"\") {
                        errors = \"true\";
                        msg += \"" . _JOBS_VALIDMESS . "\\n\";
                }
                if (errors == \"true\") {
                        msg += \"__________________________________________________\\n\\n" . _JOBS_VALIDMSG . "\\n\";
                        alert(msg);

                        return false;
                }
          }
          </script>";

    if ($xoopsUser) {
        $idd  = $xoopsUser->getVar('uname', 'E');
        $idde = $xoopsUser->getVar('email', 'E');

        $result2 = $xoopsDB->query('select lid, cid, title, private, email, submitter, resume FROM  ' . $xoopsDB->prefix('jobs_resume') . " WHERE email='$idde'");
        list($rlid, $cid, $title, $private, $email, $submitter, $resume) = $xoopsDB->fetchRow($result2);

        $rlid   = (int)$rlid;
        $title  = $myts->addSlashes($title);
        $resume = $myts->addSlashes($resume);

        echo '<b>' . _JOBS_CONTACTAUTOR . '</b><br><br>';
        echo '' . _JOBS_TEXTAUTO . '<br>';
        echo '<form onsubmit="return verify();" method="post" action="contact.php" name="cont">';
        echo "<input type=\"hidden\" name=\"id\" value=\"$lid\">";
        echo '<input type="hidden" name="submit" value="1">';
        echo "<table width='100%' class='outer' cellspacing='1'>
    <tr><input type=\"hidden\" name=\"private\" value=\"$private\">";
        echo "<td class='head'>" . _JOBS_YOURNAME . "</td>
    <td class='even'><input type=\"text\" name=\"namep\" size=\"40\" value=\"$idd\"></td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_YOUREMAIL . "</td>
    <td class='even'><input type=\"text\" name=\"post\" size=\"40\" value=\"$idde\"></td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_YOURPHONE . "</td>
    <td class='even'><input type=\"text\" name=\"tele\" size=\"40\"></td>
    </tr>";

        echo '<tr>
    <td class="head">' . _JOBS_YOURMESSAGE . ' </td><td class="odd">';
        $wysiwyg_text_area = jobs_getEditor(_JOBS_DESC2, 'messtext', '', '100%', '200px');
        echo $wysiwyg_text_area->render();
        echo '</td></tr><tr>';

        if ($rlid) {
            echo "<tr>
          <td class='head'>" . _JOBS_YOURLISTING . "</td>
          <td class='odd'><select name=\"resume\"><option value=\"0\">" . _JOBS_JOBSELECT . '</option>';

            $dropdown = $xoopsDB->query('select lid, title, private, email FROM  ' . $xoopsDB->prefix('jobs_resume') . " WHERE email='$idde' AND lid=$rlid ORDER BY date DESC");
            while (false !== (list($rlid, $title, $private, $email) = $xoopsDB->fetchRow($dropdown))) {
                echo '<option value="' . XOOPS_URL . "/modules/$moduleDirName/viewresume.php?lid=$rlid&amp;unlock=$private\">" . $title . '</option>';
            }
            echo '</select></td></tr>';
        }

        //  if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
        //  echo "<tr><td class='head'>"._JOBS_CAPTCHA." </td><td class='even'>";
        //  $jlm_captcha = "";
        //  $jlm_captcha = (new \XoopsFormCaptcha(_JOBS_CAPTCHA, "xoopscaptcha", false));
        //  echo $jlm_captcha->render();
        //  }
        //  echo "</td></tr>";
        echo "</table>
    <table class='outer'><tr><td>" . _JOBS_YOUR_IP . '&nbsp;
        <img src="' . XOOPS_URL . "/modules/$moduleDirName/ip_image.php\" alt=\"\"><br>" . _JOBS_IP_LOGGED . '
        </td></tr></table>
    <br>';
        echo "<input type=\"hidden\" name=\"private\" value=\"$private\">";
        echo "<input type=\"hidden\" name=\"ipnumber\" value=\"$ipnumber\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"$time\">";
        echo '<input type="hidden" name="submit" value="1">';
        echo '<p><input type="submit" value="' . _JOBS_SENDFR . '"></p>' . $GLOBALS['xoopsSecurity']->getTokenHTML('token') . '
    </form>';
    }
}
echo '</td></tr></table>';
include XOOPS_ROOT_PATH . '/footer.php';
