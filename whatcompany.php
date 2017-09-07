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

$myts      = MyTextSanitizer::getInstance();
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
if (!$gpermHandler->checkRight('jobs_premium', $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

$comp_id     = !isset($_REQUEST['comp_id']) ? null : $_REQUEST['comp_id'];
$member_usid = $xoopsUser->uid();

if ($xoopsUser) {
    $GLOBALS['xoopsOption']['template_main'] = 'jobs_choose_company.tpl';
    include XOOPS_ROOT_PATH . '/header.php';

    $result = $xoopsDB->query('SELECT comp_id, comp_name FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE ' . $member_usid . ' IN (comp_usid, comp_user1, comp_user2)');
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $a_comp   = [];
        $istheirs = true;
        $xoopsTpl->assign('istheirs', $istheirs);
        $xoopsTpl->assign('comp_listurl', 'addlisting.php?comp_id=');
        $a_comp['comp_id']   = $myrow['comp_id'];
        $a_comp['comp_name'] = $myrow['comp_name'];
        $xoopsTpl->append('companies', $a_comp);
        $xoopsTpl->assign('choose_company', _JOBS_MUST_CHOOSE);
    }
}
include XOOPS_ROOT_PATH . '/footer.php';
