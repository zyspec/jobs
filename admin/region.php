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

require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
xoops_load('XoopsPageNav');

$myts = MyTextSanitizer::getInstance();
#  function Index
#####################################################
function Region()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts, $moduleDirName;

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(4, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation(basename(__FILE__));
    $adminObject->addItemButton(_AM_JOBS_ADD_REGION, 'addregion.php', 'add', '');
    $adminObject->addItemButton(_AM_JOBS_LISTS, 'lists.php', 'list', '');
    $adminObject->displayButton('left', '');

    //    include XOOPS_ROOT_PATH . '/class/pagenav.php';

    $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_region') . '');
    list($crow) = $xoopsDB->fetchRow($countresult);
    $crows = $crow;

    $nav = '';
    if ($crows > '0') {
        // shows number of companies per page default = 15
        $showonpage = 50;
        $show       = '';
        $show       = ((int)$show > 0) ? (int)$show : $showonpage;

        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
        if (!isset($max)) {
            $max = $start + $show;
        }

        $sql = 'SELECT rid, pid, name, abbrev FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid';

        $result1 = $xoopsDB->query($sql, $show, $start);
        echo '<table border=1 width=100% cellpadding=2 cellspacing=0 border=0><td><tr>';
        if ($crows > 0) {
            $nav = new XoopsPageNav($crows, $showonpage, $start, 'start', 'op=Region');
            //  echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_JOBS_MAN_REGION."</legend>";
            //  echo "<br>"._AM_JOBS_THEREIS." <b>$crows</b> "._AM_JOBS_REGIONS."<br><br>";
            //  echo "<fieldset><legend style='font-weight: bold; color:#900;'>"._AM_JOBS_ADD_REGION."</legend>";
            //  echo "<br><a href=\"addregion.php\">"._AM_JOBS_ADD_REGION."</a>
            //
            //
            //<br><br><br><br><a href=\"lists.php\">"._AM_JOBS_LISTS."</a><br>";
            //echo "</td></tr>
            //</fieldset>";
            echo '<br>' . _AM_JOBS_THEREIS . " <b>$crows</b> " . _AM_JOBS_REGIONS . '<br><br>';
            //  echo "</td></tr></table>";
            echo $nav->renderNav();

            echo '<br><br><table width=100% cellpadding=2 cellspacing=0 border=0>';
            $rank = 1;
        }
        while (list($rid, $pid, $name, $abbrev) = $xoopsDB->fetchRow($result1)) {
            $name = $myts->htmlSpecialChars($name);

            if (is_int($rank / 2)) {
                $color = 'even';
            } else {
                $color = 'odd';
            }

            echo "<tr class='$color'><td><a href=\"region.php?op=ModRegion&amp;rid=$rid\">$name</a></td></tr>";
            ++$rank;
        }

        echo '</table><br>';
        echo '</fieldset><br>';
        echo $nav->renderNav();
    } else {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_REGION . '</legend>';
        echo '<br> ' . _AM_JOBS_NOREGION . '<br><br>';

        echo '<br> ' . _AM_JOBS_INSTALL_NOW . '<br><br>';
        echo "<a href=\"include/usstates.php\">" . _AM_JOBS_US_STATES . '</a><br>';
        echo "<a href=\"include/canada.php\">" . _AM_JOBS_CANADA_STATES . '</a><br>';
        echo "<a href=\"include/france.php\">" . _AM_JOBS_FRANCE . '</a><br>';
        echo "<a href=\"include/italy.php\">" . _AM_JOBS_ITALY . '</a><br>';
        echo "<a href=\"include/england.php\">" . _AM_JOBS_ENGLAND . '</a><br>';
        echo "</fieldset>

    <fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_ADD_REGION . '</legend>';
        echo "<a href=\"addregion.php\">" . _AM_JOBS_ADD_REGION . '</a></fieldset>
    </table<br>';
    }
    xoops_cp_footer();
}

#  function ModRegion
#####################################################
/**
 * @param int $rid
 */
function ModRegion($rid = 0)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $myts, $moduleDirName;

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(4, "");

    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MOD_REGION . '</legend>';

    $result = $xoopsDB->query('select rid, pid, name, abbrev from ' . $xoopsDB->prefix('jobs_region') . " where rid=$rid");

    while (list($rid, $pid, $name, $abbrev) = $xoopsDB->fetchRow($result)) {
        $name   = $myts->htmlSpecialChars($name);
        $abbrev = $myts->htmlSpecialChars($abbrev);

        echo "<form action=\"region.php\" method=\"post\">";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<table class=\"outer\" border=\"1\"><tr>
    <td class=\"head\">" . _AM_JOBS_REGION_NUMBER . " </td><td class=\"head\">$rid</td>
    </tr><tr>
    <td class=\"head\">" . _AM_JOBS_REGION_NAME . " </td><td class=\"head\"><input type=\"text\" name=\"name\" size=\"30\" value=\"$name\"></td>
    </tr><tr>
    <td class=\"head\">" . _AM_JOBS_REGION_ABBREV . " </td><td class=\"head\"><input type=\"text\" name=\"abbrev\" size=\"30\" value=\"$abbrev\"></td>
    </tr>";

        $time = time();

        echo "<tr>
    <td>&nbsp;</td><td>

    <select name=\"op\">
    <option value=\"ModRegionS\"> " . _AM_JOBS_MODIF . "
    <option value=\"RegionDel\"> " . _AM_JOBS_DEL . "
    </select><input type=\"submit\" value=\"" . _AM_JOBS_GO . "\"></td>
    </tr></table>";
        echo "<input type=\"hidden\" name=\"pid\" value=\"0\">
    <input type=\"hidden\" name=\"rid\" value=\"$rid\">";

        echo '</form><br>';
    }
    echo '</fieldset><br>';
    xoops_cp_footer();
}

#  function ModRegionS
#####################################################
/**
 * @param int $rid
 * @param int $pid
 * @param     $name
 * @param     $abbrev
 */
function ModRegionS($rid = 0, $pid = 0, $name, $abbrev)
{
    global $xoopsDB, $xoopsConfig, $xoopsModuleConfig, $myts, $moduleDirName;

    $date = time();

    $pid    = $myts->addSlashes($pid);
    $name   = $myts->addSlashes($name);
    $abbrev = $myts->addSlashes($abbrev);

    $xoopsDB->query('update ' . $xoopsDB->prefix('' . $moduleDirName . '_region') . " set pid='$pid', name='$name', abbrev='$abbrev' where rid=$rid");

    redirect_header('region.php', 3, _AM_JOBS_REGION_MODIFIED);
}

/**
 * @param int $rid
 * @param int $ok
 */
function RegionDel($rid = 0, $ok = 0)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsLogger, $moduleDirName;

    $result = $xoopsDB->query('SELECT name, abbrev FROM ' . $xoopsDB->prefix('jobs_region') . ' WHERE rid=' . $xoopsDB->escape($rid) . '');
    list($name, $abbrev) = $xoopsDB->fetchRow($result);

    $ok          = !isset($_REQUEST['ok']) ? null : $_REQUEST['ok'];
    $member_usid = $xoopsUser->getVar('uid', 'E');
    if (1 == $ok) {

        // Delete Region
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_region') . ' WHERE rid=' . $xoopsDB->escape($rid) . '');

        redirect_header('region.php', 3, _AM_JOBS_REGION_DEL);
    } else {
        xoops_cp_header();
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
        echo '<br><center>';
        echo '<b>' . _AM_JOBS_SURDELREGION . '</b><br><br>';
    }
    echo "[ <a href=\"region.php?op=RegionDel&amp;rid=" . addslashes($rid) . "&amp;ok=1\">" . _AM_JOBS_YES . "</a> | <a href=\"index.php\">" . _AM_JOBS_NO . '</a> ]<br><br>';
    echo '</td></tr></table>';
    xoops_cp_footer();
}

#####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if (!isset($_POST['rid']) && isset($_GET['rid'])) {
    $rid = $_GET['rid'];
}
if (!isset($_POST['ok']) && isset($_GET['ok'])) {
    $ok = $_GET['ok'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {

    case 'ModRegion':
        ModRegion($rid);
        break;

    case 'ModRegionS':
        ModRegionS($rid, $pid, $name, $abbrev);
        break;

    case 'RegionDel':
        RegionDel($rid);
        break;

    default:
        Region();
        break;
}
