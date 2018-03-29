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

require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/restree.php";

$myts = \MyTextSanitizer::getInstance();

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_JOBS_RES_ADD_LINK, 'addresume.php', 'add', '');
$adminObject->displayButton('left', '');
//loadModuleAdminMenu(3, "");

//include XOOPS_ROOT_PATH . '/class/pagenav.php';

$countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_resume') . '');
list($crow) = $xoopsDB->fetchRow($countresult);
$crows = $crow;

$nav = '';
if ($crows > '0') {
    // shows number of resumes per page set in preferences
    $showonpage = $helper->getConfig('jobs_reslisting_num');

    $show = '';
    $show = ((int)$show > 0) ? (int)$show : $showonpage;

    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
    if (!isset($max)) {
        $max = $start + $show;
    }

    $sql = 'SELECT lid, title, name, date, status, expire, submitter, valid, resume FROM ' . $xoopsDB->prefix('jobs_resume') . ' ORDER BY lid';

    $result1 = $xoopsDB->query($sql, $show, $start);
    echo '<table border=1 width=100% cellpadding=2 cellspacing=0 border=0><td><tr>';

    if ($crows > 0) {
        $nav = new \XoopsPageNav($crows, $showonpage, $start, 'start', '');
        echo '<br>' . _AM_JOBS_THEREIS . " <b>$crows</b> " . _AM_JOBS_RES_LISTINGS . '<br><br>';
        echo $nav->renderNav();
        echo '<br><br><table width=100% cellpadding=2 cellspacing=0 border=0>';
        $rank = 1;
    }

    echo "<table width='100%' cellspacing='1' class='outer'>
                 <tr>
                     <th align=\"center\">" . _AM_JOBS_RES_NUMBER . '</th>
                     <th align="center">' . _AM_JOBS_RES_TITLE . '</th>
                     <th align="center">' . _AM_JOBS_RES_NAME . '</th>
                         <th align="center">' . _AM_JOBS_SUBMITTED_ON . '</th>
                         <th align="center">' . _AM_JOBS_EXPIRES . '</th>
                         <th align="center">' . _AM_JOBS_ACTIVE . '</th>
                         <th align="center">' . _AM_JOBS_SUBMITTER . '</th>
                         <th align="center">' . _AM_JOBS_PUBLISHEDCAP . '</th>
                         <th align="center">' . _AM_JOBS_RESUME . "</th>

                     <th align='center' width='10%'>" . _AM_JOBS_ACTIONS . '</th>
                 </tr>';

    $class   = 'odd';
    $result1 = $xoopsDB->query($sql, $show, $start);
    while (false !== (list($lid, $title, $name, $date, $status, $expire, $submitter, $valid, $resume) = $xoopsDB->fetchRow($result1))) {
        $name  = $myts->htmlSpecialChars($name);
        $title = $myts->htmlSpecialChars($title);
        $date2 = formatTimestamp($date, 's');
        //$expire2     = formatTimestamp($expire, "s");

        echo "<tr class='" . $class . "'>";
        $class = ('even' === $class) ? 'odd' : 'even';
        echo "<td align=\"center\">$lid</td>";
        echo '<td align="center">' . $title . '</td>';
        echo '<td align="center">' . $name . '</td>';
        echo '<td align="center">' . $date2 . '</td>';
        echo '<td align="center">' . $expire . '</td>';
        echo '<td align="center">' . $status . '</td>';
        echo '<td align="center">' . $submitter . '</td>';
        echo '<td align="center">' . $valid . '</td>';
        echo '<td align="center">' . $resume . '</td>';

        echo "<td align='center' width='10%'>
                         <a href='modresume.php?lid=" . $lid . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                         <a href='../delresume.php?lid=" . $lid . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                         </td>";
        echo '</tr>';
    }
    echo '</table><br><br>';
    echo $nav->renderNav();
//    echo "</fieldset><br>";
} else {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_RESUME . '</legend>';
    echo '<br> ' . _AM_JOBS_NO_RESUME . '<br><br>';
    echo '</fieldset>';

    //echo  "<fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_ADD_COMPANY . "</legend>";
    //    echo "<a href=\"addcomp.php\">" . _AM_JOBS_ADD_COMPANY . "</a></fieldset>
    //  </table<br>";
}

require_once __DIR__ . '/resume_categories.php';

xoops_cp_footer();
