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

//
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //

//require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';

$moduleDirName = basename(dirname(__DIR__));

//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/restree.php";
$mytree  = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');
$restree = new JobTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');

global $mytree, $restree, $xoopsDB, $xoopsModuleConfig, $moduleDirName;

require_once __DIR__ . '/admin_header.php';
$myts = MyTextSanitizer::getInstance();
xoops_cp_header();
//    loadModuleAdminMenu(1, "");
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_JOBS_ADDSUBCAT, 'category.php?op=ModCat&cid=0', 'add', '');
//$adminObject->addItemButton(_AM_JOBS_ADDCATPRINC, 'lists.php', 'list', '');
$adminObject->displayButton('left', '');

echo "<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_CATEGORY . '</legend>';
echo '<br><a href="category.php?op=NewCat&amp;cid=0"><img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/plus.gif\" border=0 width=10 height=10  alt=\"" . _AM_JOBS_ADDSUBCAT . '"></a> ' . _AM_JOBS_ADDCATPRINC . '<br><br>';

$mytree->makeJobSelBox('title', '' . $xoopsModuleConfig['jobs_cat_sortorder'] . '');

echo '<br><hr>';
echo '<p>' . _AM_JOBS_HELP1 . ' </p>';

if ($xoopsModuleConfig['jobs_cat_sortorder'] == 'ordre') {
    echo '<p>' . _AM_JOBS_HELP2 . ' </p>';
}
echo '<br></fieldset><br>';
echo "<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_RES_CATEGORY . '</legend>';
echo '<br><a href="category.php?op=NewResCat&amp;cid=0"><img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/plus.gif\" border=0 width=10 height=10  alt=\"" . _AM_JOBS_ADDSUBCAT . '"></a> ' . _AM_JOBS_ADDCATPRINC . '<br><br>';

$restree->makeResSelBox('title', '' . $xoopsModuleConfig['jobs_cat_sortorder'] . '');
echo '<br></fieldset><br>';

//-------------------------------------------------------
//include XOOPS_ROOT_PATH . '/class/pagenav.php';

$countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_res_categories') . '');

list($crow) = $xoopsDB->fetchRow($countresult);
$crows = $crow;

$nav = '';
if ($crows > '0') {
    // shows number of companies per page default = 15
    $showonpage = 15;
    $show       = '';
    $show       = ((int)$show > 0) ? (int)$show : $showonpage;

    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
    if (!isset($max)) {
        $max = $start + $show;
    }

    $sql = 'SELECT cid, pid, title, img, ordre, affprice FROM ' . $xoopsDB->prefix('jobs_res_categories') . ' ORDER BY cid';

    $result1 = $xoopsDB->query($sql, $show, $start);
    echo '<table border=1 width=100% cellpadding=2 cellspacing=0 border=0><td><tr>';

    echo "<table width='100%' cellspacing='1' class='outer'>
    <tr>
        <th align=\"center\">" . _AM_JOBS_RES_NUMBER . '</th>
        <th align="center">' . _AM_JOBS_RES_TITLE . '</th>
        <th align="center">' . _AM_JOBS_RES_NAME . '</th>
            <th align="center">' . _AM_JOBS_SUBMITTED_ON . '</th>
            <th align="center">' . _AM_JOBS_EXPIRES . '</th>
            <th align="center">' . _AM_JOBS_ACTIVE . '</th>
            <th align="center">' . _AM_JOBS_SUBMITTER . "</th>

        <th align='center' width='10%'>" . _AM_JOBS_ACTIONS . '</th>
    </tr>';

    $class   = 'odd';
    $result1 = $xoopsDB->query($sql, $show, $start);
    while (list($cid, $pid, $title, $img, $ordre, $affprice) = $xoopsDB->fetchRow($result1)) {
        $title = $myts->htmlSpecialChars($title);

        //$expire2     = formatTimestamp($expire, "s");

        echo "<tr class='" . $class . "'>";
        $class = ($class == 'even') ? 'odd' : 'even';
        echo "<td align=\"center\">$lid</td>";
        echo '<td align="center">' . $cid . '</td>';
        echo '<td align="center">' . $pid . '</td>';
        echo '<td align="center">' . $title . '</td>';
        echo '<td align="center">' . $img . '</td>';
        echo '<td align="center">' . $ordre . '</td>';
        echo '<td align="center">' . $affprice . '</td>';

        echo "<td align='center' width='10%'>
            <a href='modresume.php?lid=" . $lid . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
            <a href='../delresume.php?lid=" . $lid . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
            </td>";
        echo '</tr>';
    }
    echo '</table><br><br>';
    //    echo "</fieldset><br>";
} else {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_COMPANY . '</legend>';
    echo '<br> ' . _AM_JOBS_NOCOMPANY . '<br><br>';
    echo "</fieldset>

    <fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_ADD_COMPANY . '</legend>';
    echo '<a href="addcomp.php">' . _AM_JOBS_ADD_COMPANY . '</a></fieldset>
    </table<br>';
}

//-----------------------
xoops_cp_footer();
