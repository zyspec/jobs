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

use XoopsModules\Jobs;
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

include __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
$myts = \MyTextSanitizer::getInstance();
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/resume_functions.php";
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
if (!$gpermHandler->checkRight('resume_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/resumes.php", 3, _NOPERM);
}

if (!empty($_POST['submit'])) {
    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/index.php", 3, $GLOBALS['xoopsSecurity']->getErrors());
    }
    if ('dhtmltextarea' === $helper->getConfig('jobs_resume_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $made_resume = $myts->displayTarea($_POST['made_resume'], 0, 0, 0, 0, 0);
    } else {
        $made_resume = $myts->displayTarea($_POST['made_resume'], 1, 1, 1, 1, 1);
    }

    //    $lid=$_POST['lid'];
    $lid = !isset($_REQUEST['lid']) ? null : $_REQUEST['lid'];

    $xoopsDB->query('update ' . $xoopsDB->prefix('jobs_created_resumes') . " set made_resume='$made_resume' where lid=" . $xoopsDB->escape($lid) . '');

    redirect_header("myresume.php?lid=$lid", 3, _JOBS_RES_MOD);
//redirect_header("myresume.php", 3, _JOBS_RES_MOD);
} else {
    include XOOPS_ROOT_PATH . '/header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $lid = !isset($_REQUEST['lid']) ? null : $_REQUEST['lid'];

    $result = $xoopsDB->query('SELECT res_lid, lid, made_resume, date, usid FROM ' . $xoopsDB->prefix('jobs_created_resumes') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
    list($res_lid, $lid, $made_resume, $date, $usid) = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->uid();
        if ($usid == $calusern) {
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _JOBS_EDIT_RESUME . '</legend>';

            if ('dhtmltextarea' === $helper->getConfig('jobs_resume_options')
                || 'dhtml' === $helper->getConfig('jobs_form_options')) {
                $made_resume = $myts->undoHtmlSpecialChars($myts->displayTarea($made_resume, 0, 0, 0, 0, 0));
            } else {
                $made_resume = $myts->displayTarea($made_resume, 1, 1, 1, 1, 1);
            }

            $dates = formatTimestamp($date, 's');

            echo '<form action="modcreated.php" method=post enctype="multipart/form-data">';
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo '<table class="outer"><tr>
        <td class="odd" width="35%">' . _JOBS_NUMANNN . " </td><td class=\"odd\">$lid " . _JOBS_DU . " $dates</td>
        </tr><tr>
        <td class=\"even\" width=\"35%\">" . _JOBS_RESUME . ' </td><td class="even">';

            $wysiwyg_text_area = resume_getEditor(_JOBS_RESUME, 'made_resume', $made_resume, '100%', '200px', 'small');
            echo $wysiwyg_text_area->render();

            echo '</td></tr><tr>';
            echo '<td colspan=2><br><br><input type="submit" value="' . _JOBS_RES_MODIFANN . '"></td>
        </tr></table>';
            echo '<input type="hidden" name="submit" value="1">';
            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
            echo "<input type=\"hidden\" name=\"date\" value=\"$date\">
        " . $GLOBALS['xoopsSecurity']->getTokenHTML('token') . '';
            echo '</form><br>';
            echo '</fieldset><br>';
        }
    }

    include XOOPS_ROOT_PATH . '/footer.php';
}
