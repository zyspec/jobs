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

include __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
$lid       = !isset($_REQUEST['lid']) ? null : $_REQUEST['lid'];

if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('resume_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/resumes.php", 3, _NOPERM);
}

include XOOPS_ROOT_PATH . '/header.php';
$result = $xoopsDB->query('SELECT usid, resume FROM ' . $xoopsDB->prefix('jobs_resume') . ' WHERE lid=' . $xoopsDB->escape($lid) . ' ');
list($usid, $resume) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    $ok       = !isset($_REQUEST['ok']) ? null : $_REQUEST['ok'];
    $calusern = $xoopsUser->getVar('uid', 'E');
    if ($usid == $calusern || $xoopsUser->isAdmin()) {
        if (1 == $ok) {
            if ('created' === $resume) {
                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_created_resumes') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
            } else {
                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_resume') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
            }

            if ($resume) {
                $destination = XOOPS_ROOT_PATH . "/modules/$moduleDirName/resumes";
                if (file_exists("$destination/$resume")) {
                    unlink("$destination/$resume");
                }
            }
            redirect_header('resumes.php', 3, _JOBS_RES_JOBDEL);
        } else {
            echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
            echo '<br><center>';
            echo '<b>' . _JOBS_SURDELRES . '</b><br><br>';
        }
        echo '[ <a href="delresume.php?lid=' . addslashes($lid) . '&amp;ok=1">' . _JOBS_OUI . '</a> | <a href="resumes.php">' . _JOBS_NON . '</a> ]<br><br>';
        echo '</td></tr></table>';
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
