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

// _________________________________________________________________________ //
//                                                                           //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller                                    //
// Author Website : pascal.e-xoops@perso-search.com                          //
// Licence Type   : GPL                                                      //
// ------------------------------------------------------------------------- //

if (isset($_POST['submit'])) {
    // Define Variables for register_globals Off
    $id        = !isset($_REQUEST['id']) ? null : $_REQUEST['id'];
    $date      = !isset($_REQUEST['date']) ? null : $_REQUEST['date'];
    $namep     = !isset($_REQUEST['namep']) ? null : $_REQUEST['namep'];
    $ipnumber  = !isset($_REQUEST['ipnumber']) ? null : $_REQUEST['ipnumber'];
    $message   = !isset($_REQUEST['message']) ? null : $_REQUEST['message'];
    $typeprice = !isset($_REQUEST['typeprice']) ? null : $_REQUEST['typeprice'];
    $price     = !isset($_REQUEST['price']) ? null : $_REQUEST['price'];
    $private   = !isset($_REQUEST['private']) ? null : $_REQUEST['private'];
    $messtext  = !isset($_REQUEST['messtext']) ? null : $_REQUEST['messtext'];
    $tele      = !isset($_REQUEST['tele']) ? null : $_REQUEST['tele'];
    $post      = !isset($_REQUEST['post']) ? null : $_REQUEST['post'];
    $resume    = !isset($_REQUEST['resume']) ? null : $_REQUEST['resume'];
    $company   = !isset($_REQUEST['company']) ? null : $_REQUEST['company'];
    $listing   = !isset($_REQUEST['listing']) ? null : $_REQUEST['listing'];
    // end define vars

    include __DIR__ . '/header.php';

    $moduleDirName = basename(__DIR__);

    $module_id = $xoopsModule->getVar('mid');
    if (is_object($xoopsUser)) {
        $groups =& $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gpermHandler = xoops_getHandler('groupperm');
    if (isset($_POST['item_id'])) {
        $perm_itemid = (int)$_POST['item_id'];
    } else {
        $perm_itemid = 0;
    }
    //If no access
    if (!$gpermHandler->checkRight('' . $moduleDirName . '_view', $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }

//    require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";

    global $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $myts, $moduleDirName;

    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/viewresume.php?lid=" . addslashes($id) . '', 3, $GLOBALS['xoopsSecurity']->getErrors());
    }

    //  if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
    //  $x24plus = jobs_isX24plus();
    //  if ($x24plus) {
    //  xoops_load("xoopscaptcha");
    //  $xoopsCaptcha = XoopsCaptcha::getInstance();
    //  if ( !$xoopsCaptcha->verify() ) {
    //       redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, $xoopsCaptcha->getMessage() );
    //  }
    //  } else {
    //  xoops_load("captcha");
    //  $xoopsCaptcha = XoopsCaptcha::getInstance();
    //  if ( !$xoopsCaptcha->verify() ) {
    //        redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, $xoopsCaptcha->getMessage() );
    //  }
    //  }
    //  }
    $result = $xoopsDB->query('SELECT name, email, submitter, title FROM  ' . $xoopsDB->prefix('jobs_resume') . ' WHERE lid = ' . $xoopsDB->escape($id) . '');

    while (list($name, $email, $submitter, $title) = $xoopsDB->fetchRow($result)) {
        $name      = $myts->addSlashes($name);
        $title     = $myts->addSlashes($title);
        $submitter = $myts->addSlashes($submitter);

        $message .= "$name " . _JOBS_RES_REPLY . ' ' . _JOBS_FROMANNOF . ' ' . $xoopsConfig['sitename'] . "\n\n";
        $message .= '' . _JOBS_MESSFROM . " $namep " . _JOBS_FOR . " $company \n\n";
        $message .= "\n";
        $message .= stripslashes("$messtext\n\n");
        $message .= '   ' . _JOBS_ENDMESS . "\n\n";
        if ('0' != $listing) {
            $message .= '' . _JOBS_RES_LISTING . "\n\n";
            $message .= "$listing\n\n";
        }
        $message .= '' . _JOBS_CANJOINT . " $namep " . _JOBS_TO . " $post " . _JOBS_ORAT . " $tele \n\n";
        $message .= "End of message \n\n";

        $subject = '' . _JOBS_RES_CONTACTAFTER . '';
        $mail    = xoops_getMailer();
        $mail->useMail();
        $mail->setFromEmail($post);
        $mail->setToEmails($email);
        $mail->setSubject($subject);
        $mail->setBody($message);
        $mail->send();
        echo $mail->getErrors();

        if ($xoopsModuleConfig['jobs_admin_mail'] = 1) {
            $message     .= "\n" . $_SERVER['REMOTE_ADDR'] . "\n";
            $adsubject   = $xoopsConfig['sitename'] . ' Job Reply ';
            $xoopsMailer = xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
            $xoopsMailer->setFromName($xoopsConfig['sitename']);
            $xoopsMailer->setSubject($adsubject);
            $xoopsMailer->setBody($message);
            $xoopsMailer->send();
        }
    }
    redirect_header('resumes.php', 3, _JOBS_MESSEND);
} else {
    $lid = isset($_GET['lid']) ? (int)$_GET['lid'] : '';

    include __DIR__ . '/header.php';

    $moduleDirName = basename(__DIR__);
    $module_id     = $xoopsModule->getVar('mid');
    if (is_object($xoopsUser)) {
        $groups =& $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gpermHandler = xoops_getHandler('groupperm');
    if (isset($_POST['item_id'])) {
        $perm_itemid = (int)$_POST['item_id'];
    } else {
        $perm_itemid = 0;
    }
    //If no access
    if (!$gpermHandler->checkRight('' . $moduleDirName . '_view', $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    }

//    require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    global $xoopsConfig, $xoopsUser, $xoopsDB, $myts, $moduleDirName;

    include XOOPS_ROOT_PATH . '/header.php';
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
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
        echo '<b>' . _JOBS_RES_CONTACTHEAD . '</b><br><br>';
        echo '' . _JOBS_RES_TOREPLY . '<br>';
        echo '<form onsubmit="return verify();" method="post" action="contactresume.php" name="cont">';
        echo "<input type=\"hidden\" name=\"id\" value=\"$lid\">";
        echo '<input type="hidden" name="submit" value="1">';

        $idd  = $xoopsUser->getVar('uname', 'E');
        $idde = $xoopsUser->getVar('email', 'E');

        $result1 = $xoopsDB->query('select lid, cid, title, company, email, submitter FROM  ' . $xoopsDB->prefix('jobs_listing') . " WHERE email = '$idde' and lid = " . $xoopsDB->escape($lid) . '');
        list($lid, $cid, $title, $company, $email, $submitter) = $xoopsDB->fetchRow($result1);

        $title = $myts->addSlashes($title);

        echo "<table width='100%' class='outer' cellspacing='1'>
    <tr>
      <td class='head'>" . _JOBS_RES_NAME . "</td>
      <td class='even'><input type=\"text\" name=\"namep\" size=\"40\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_COMPANY . "</td>
      <td class='even'><input type=\"text\" name=\"company\" size=\"40\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_RES_UNAME . "</td>
      <td class='even'>$idd</td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOUREMAIL . "</td>
      <td class='even'><input type=\"text\" name=\"post\" size=\"40\" value=\"$idde\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOURPHONE . "</td>
      <td class='even'><input type=\"text\" name=\"tele\" size=\"40\"></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_RES_YOURMESSAGE . "</td>
      <td class='even'><textarea rows=\"5\" name=\"messtext\" cols=\"40\"></textarea></td>
    </tr>";
        if ($result1 >= 1) {
            echo "<tr>
      <td class='head'>" . _JOBS_YOURLISTING . "</td>
      <td class='odd'><select name=\"listing\"><option value=\"0\">" . _JOBS_RES_JOBSELECT . '</option>';

            $dropdown = $xoopsDB->query('select lid, title, date, email FROM  ' . $xoopsDB->prefix('jobs_listing') . " WHERE email = '$idde' ORDER BY date DESC");
            while (list($lid, $title, $date, $email) = $xoopsDB->fetchRow($dropdown)) {
                echo '<option value="' . XOOPS_URL . '/modules/' . $moduleDirName . "/viewjobs.php?lid=$lid\">" . $title . '</option>';
            }
            echo '</select></td></tr>';
        }

        //  if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
        //      echo "<tr><td class='head'>"._JOBS_CAPTCHA." </td><td class='even'>";
        //  $jlm_captcha = "";
        //  $jlm_captcha = (new XoopsFormCaptcha(_JOBS_CAPTCHA, "xoopscaptcha", false));
        //  echo $jlm_captcha->render();
        //  }
        echo "</td></tr></table>
    <table class='outer'><tr><td>" . _JOBS_YOUR_IP . '&nbsp;
        <img src="' . XOOPS_URL . "/modules/$moduleDirName/ip_image.php\" alt=\"\"><br>" . _JOBS_IP_LOGGED . '
        </td></tr></table>
    <br>
      <p><input type="submit" name="submit" value="' . _JOBS_SENDFR . '"></p>' . $GLOBALS['xoopsSecurity']->getTokenHTML('token') . '
    </form>';
    }
    echo '</td></tr></table>';
    include XOOPS_ROOT_PATH . '/footer.php';
}
