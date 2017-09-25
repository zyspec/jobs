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
$myts = MyTextSanitizer::getInstance();
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

$result = $xoopsDB->query('SELECT made_resume, usid FROM ' . $xoopsDB->prefix('jobs_created_resumes') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
list($made_resume, $usid) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    include XOOPS_ROOT_PATH . '/header.php';
    $calusern = $xoopsUser->getVar('uid', 'E');
    if ($usid == $calusern) {
        if (1 == $ok) {
            $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_created_resumes') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
            if ($made_resume) {
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
        echo '[ <a href="editresume.php?op=CreatedDel&amp;lid=' . addslashes($lid) . '&amp;ok=1">' . _JOBS_OUI . '</a> | <a href="resumes.php">' . _JOBS_NON . '</a> ]<br><br>';
        echo '</td></tr></table>';
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
