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

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('jobs_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/index.php", 3, _NOPERM);
}
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

$comp_id = (\Xmf\Request::getInt('comp_id', 0, 'GET') > 0) ? \Xmf\Request::getInt('comp_id', 0, 'GET') : 0;

include XOOPS_ROOT_PATH . '/header.php';

$result = $xoopsDB->query('SELECT comp_name, comp_usid, comp_img FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE comp_id=' . $xoopsDB->escape($comp_id) . '');
list($comp_name, $comp_usid, $photo) = $xoopsDB->fetchRow($result);

$result1 = $xoopsDB->query('SELECT company FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE usid=' . $xoopsDB->escape($comp_usid) . '');
list($my_company) = $xoopsDB->fetchRow($result1);

if ($xoopsUser) {
    $ok = !isset($_REQUEST['ok']) ? null : $_REQUEST['ok'];

    $member_usid = $xoopsUser->getVar('uid', 'E');
    if ($comp_usid == $member_usid) {
        if (1 == $ok) {
            // Delete Company
            $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE comp_id=' . $xoopsDB->escape($comp_id) . '');

            // Delete all listing by Company
            if ($comp_name == $my_company) {
                $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE usid=' . $xoopsDB->escape($comp_usid) . '');
            }
            // Delete Company logo
            if ($photo) {
                $destination = XOOPS_ROOT_PATH . "/modules/$moduleDirName/logo_images";
                if (file_exists("$destination/$photo")) {
                    unlink("$destination/$photo");
                }
            }
            redirect_header('index.php', 3, _JOBS_COMPANY_DEL);
        } else {
            echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
            echo '<br><center>';
            echo '<b>' . _JOBS_SURDELCOMP . '</b><br><br>';
        }
        echo '[ <a href="delcompany.php?comp_id=' . addslashes($comp_id) . '&amp;ok=1">' . _JOBS_OUI . '</a> | <a href="members.php?comp_id=' . addslashes($comp_id) . '">' . _JOBS_NON . '</a> ]<br><br>';
        echo '</td></tr></table>';
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
