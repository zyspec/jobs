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
 * @author      John Mordo
 * @author      XOOPS Development Team
 */

//require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$myts = \MyTextSanitizer::getInstance();

xoops_cp_header();

if (!empty($_POST['comp_id'])) {
    $comp_id = \Xmf\Request::getInt('comp_id', 0, 'POST');
} elseif (!empty($_GET['comp_id'])) {
    $comp_id = \Xmf\Request::getInt('comp_id', 0, 'GET');
} else {
    $comp_id = '';
}
if (!empty($_POST['comp_name'])) {
    $comp_name = $_POST['comp_name'];
} else {
    $comp_name = '';
}
$result = $xoopsDB->query('SELECT comp_name, comp_usid, comp_img FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE comp_id=' . $xoopsDB->escape($comp_id) . '');
list($comp_name, $comp_usid, $photo) = $xoopsDB->fetchRow($result);

$result1 = $xoopsDB->query('SELECT company, usid FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE usid=' . $xoopsDB->escape($comp_usid) . '');
list($their_company, $usid) = $xoopsDB->fetchRow($result1);

$ok = !isset($_REQUEST['ok']) ? null : $_REQUEST['ok'];

if (1 == $ok) {

    // Delete Company
    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE comp_id=' . $xoopsDB->escape($comp_id) . '');

    // Delete all listing by Company
    if ($comp_name == $their_company) {
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE usid=' . $xoopsDB->escape($comp_usid) . '');
    }

    // Delete Company logo

    if ($photo) {
        $destination = XOOPS_ROOT_PATH . "/modules/$moduleDirName/logo_images";
        if (file_exists("$destination/$photo")) {
            unlink("$destination/$photo");
        }
    }
    redirect_header('company.php', 13, _AM_JOBS_COMPANY_DEL);
} else {
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo '<br><center>';
    echo '<b>' . _AM_JOBS_SURECOMP . '' . $comp_name . '' . _AM_JOBS_SURECOMPEND . '</b><br><br>';
    //  }
    echo "[ <a href=\"delcomp.php?comp_id=$comp_id&amp;ok=1\">" . _AM_JOBS_YES . '</a> | <a href="index.php">' . _AM_JOBS_NO . '</a> ]<br><br>';
    echo '</td></tr></table>';
}

xoops_cp_footer();
