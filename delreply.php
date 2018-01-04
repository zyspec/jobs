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
if (!$gpermHandler->checkRight('jobs_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/index.php", 3, _NOPERM);
}
include XOOPS_ROOT_PATH . '/header.php';

$r_lid = isset($_GET['r_lid']) ? (int)$_GET['r_lid'] : 0;
$ok    = isset($_GET['ok']) ? (int)$_GET['ok'] : 0;

$result = $xoopsDB->query('SELECT lid, r_usid FROM ' . $xoopsDB->prefix('jobs_replies') . ' WHERE r_lid=' . $xoopsDB->escape($r_lid) . '');
list($lid, $usid) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    $member_id = $xoopsUser->getVar('uid', 'E');

    $request1 = $xoopsDB->query('SELECT comp_usid, comp_user1, comp_user2 FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE ' . $member_id . ' IN (comp_usid, comp_user1, comp_user2)');
    list($comp_usid, $comp_user1, $comp_user2) = $xoopsDB->fetchRow($request1);
    $comp_users = [$comp_usid, $comp_user1, $comp_user2];
    if (in_array($member_id, $comp_users)) {
        if (1 == $ok) {
            $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_replies') . ' WHERE r_lid=' . $xoopsDB->escape($r_lid) . '');

            redirect_header('index.php', 3, _JOBS_JOBDEL);
        } else {
            echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
            echo '<br><center>';
            echo '<b>' . _JOBS_SURDELREPLY . '</b><br><br>';
        }
        echo '[ <a href="delreply.php?r_lid=' . addslashes($r_lid) . '&amp;ok=1">' . _JOBS_OUI . '</a> | <a href="replies.php?lid=' . addslashes($lid) . '">' . _JOBS_NON . '</a> ]<br><br>';
        echo '</td></tr></table>';
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
