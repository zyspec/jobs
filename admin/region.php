<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Jobs for XOOPS
 *
 * @package     \XoopsModules\Jobs
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author      John Mordo aka jlm69 (www.jlmzone.com )
 * @author      XOOPS Development Team
 * @link        https://github.com/XoopsModules25x/jobs
 */

use XoopsModules\Jobs;
use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
xoops_load('XoopsPageNav');

/**
 * Vars defined through inclusion of ./admin_header.php
 *
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Jobs\Helper $helper
 *
 * @var \XoopsModules\Jobs\PriceHandler $price_handler
 * @var \XoopsModules\Jobs\JobTypeHandler $type_handler
 * @var \XoopsModules\Jobs\JobCategoryHandler $category_handler
 * @var \XoopsModules\Jobs\ResumeCategoryHandler $resume_cat_handler
 *
 * @var \MyTextSanitizer $myts
 * @var string $moduleDirName
 */

$rid = Request::getInt('rid', Request::getInt('rid', 0, 'GET'), 'POST');
$ok  = Request::getInt('ok', Request::getInt('ok', 0, 'GET'), 'POST');
$op  = Request::getCmd('op', Request::getCmd('op', '', 'GET'), 'POST');

/** @var \XoopsModules\Jobs\RegionHandler $region_handler */
$region_handler = $helper->getHandler('Region');

switch ($op) {
    case 'modregion':
        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();

        if (0 === $rid) {
            $helper->redirect('admin/region.php', 3, 'Invalid Region Id');
        }
        /** @var \XoopsModules\Jobs\Region $region_obj */
        $region_obj = $region_handler->get($rid);

        echo "\n<fieldset>\n"
           . "<legend class='bold' style='color: #900;'>" . _AM_JOBS_MOD_REGION . "</legend>\n"
           . "<form action='" . $helper->url('admin/region.php') . "' method='post'>\n"
           . $GLOBALS['xoopsSecurity']->getTokenHTML() . "\n"
           . "<table class='outer border'>\n"
           . "  <tr>\n"
               . "    <td class='head'>" . _AM_JOBS_PARENT_NUMBER . " </td><td class='head'>" . $region_obj->getVar('pid') . "</td>\n"
           . "  </tr><tr>\n"
           . "  <tr>\n"
           . "    <td class='head'>" . _AM_JOBS_REGION_NUMBER . " </td><td class='head'>{$rid}</td>\n"
           . "  </tr><tr>\n"
           . "    <td class='head'>" . _AM_JOBS_REGION_NAME . " </td><td class='head'><input type='text' name='name' size='30' value='" . $region_obj->getVar('name') . "'></td>\n"
           . "  </tr><tr>\n"
           . "    <td class='head'>" . _AM_JOBS_REGION_ABBREV . " </td><td class='head'><input type='text' name='abbrev' size='30' value='" . $region_obj->getVar('abbrev') . "'></td>\n"
           . "  </tr><tr>\n"
           . "    <td>&nbsp;</td><td>\n"
           . "      <select name='op'>\n"
           . "        <option value='ModRegionS'> " . _AM_JOBS_MODIF . "</option>\n"
           . "        <option value='RegionDel'> " . _AM_JOBS_DEL . "</option>\n"
           . "      </select>\n"
           . "      <input type='submit' value='" . _AM_JOBS_GO . "'>\n"
           . "    </td>\n"
           . "  </tr>\n"
           . "</table>\n"
           . "<input type='hidden' name='pid' value='0'>\n"
           . "<input type='hidden' name='rid' value='{$rid}'>\n"
           . "</form><br>\n"
           . "</fieldset><br>\n";
        break;

    case 'modregions':
        // test XoopsSecurity token
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/region.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        $name   = Request::getString('name', '', 'POST');
        $abbrev = Request::getString('abbrev', '', 'POST');
        //$pid    = Request::getInt('pid', 0, 'POST');

        /** @var \XoopsModules\Jobs\Region $region_obj */
        $region_obj = $region_handler->get($rid);
        $msg = _AM_JOBS_REGION_INVALID;
        if ($region_obj instanceof \XoopsModules\Jobs\Region) {
            $region_obj->setVars([
                //'pid'    => $pid,
                'name'   => $myts->htmlSpecialChars($name),
                'abbrev' => $myts->htmlSpecialChars($abbrev)
            ]);
            $region_handler->insert($region_obj);
            $msg = _AM_JOBS_REGION_MODIFIED;
            if (!empty($region_obj->getErrors())) {
                    $msg = _AM_JOBS_REGION_INVALID . '<br>' . $region_obj->getHtmlErrors();
            }
        }
        $helper->redirect('admin/region.php', 3, $msg);
        break;

    case 'regiondel':
        if (1 === $ok) {
            // test XoopsSecurity token
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/region.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $region_handler = $helper->getHandler('Region');
            $region_obj     = $region_handler->get($rid);
            if ($region_obj instanceof \XoopsModules\Jobs\Region) {
                // object exists so now we can delete it
                $region_handler->delete($region_obj);
                $msg = _AM_JOBS_REGION_DEL;
            } else {
                // invalid region id - could not delete
                $msg = _AM_JOBS_REGION_INVALID;
            }
            $helper->redirect('admin/region.php', 3, $msg);
        }
        xoops_cp_header();
        xoops_confirm(['op' => 'RegionDel', 'rid' => $rid, 'ok' => 1], $_SERVER['SCRIPT_NAME'], _AM_JOBS_SURDELREGION, _YES);
        break;

    case 'regionadd':
        if (!empty($_POST['submit'])) {
            // test XoopsSecurity token
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/region.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            $name   = Request::getString('name', '', 'POST');
            $pid    = Request::getInt('pid', 0, 'POST');
            $abbrev = Request::getString('abbrev', '', 'POST');

            /** @var \XoopsModules\Jobs\Region $region_obj */
            $region_obj = $region_handler->create();
            $region_obj->setVars([
                'pid'  => $pid,
                'name' => $name,
                'abbrev' => $abbrev
            ]);
            $region_handler->insert($region_obj);
            $msg = _AM_JOBS_REGIONS . _AM_JOBS_CANNOT_DEL;
            if (false !== $success) {
                $msg = _AM_JOBS_REGION_ADDED;
            }
            $helper->redirect('admin/region.php', 3, $msg);
        } else {
            xoops_cp_header();
            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(__FILE__);
            $form = new \XoopsThemeForm(_AM_JOBS_ADD_REGION, 'regionform', basename(__FILE__), 'post', true);
            $form->setExtra('enctype="multipart/form-data"');
            $form->addElement(new \XoopsFormHidden('op', 'RegionAdd'));
            $form->addElement(new \XoopsFormHidden('pid', '0'));
            $form->addElement(new \XoopsFormText(_AM_JOBS_REGION_NAME, 'name', 20, 50, ''), true);
            $form->addElement(new \XoopsFormText(_AM_JOBS_REGION_ABBREV, 'abbrev', 2, 4, ''), false);
            $form->addElement(new \XoopsFormButton('', 'submit', _AM_JOBS_ADDREGION, 'submit'));
            echo $form->display();
        }
        break;

    case 'region': // Region
    default:
        require_once __DIR__ . '/admin_header.php';
        xoops_cp_header();

        /** @var \Xmf\Module\Admin $adminObject */
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_JOBS_ADD_REGION, 'region.php?op=RegionAdd', 'add');
        $adminObject->addItemButton(_AM_JOBS_LISTS, 'lists.php', 'list');
        $adminObject->displayButton('left');

        $regionCount = $region_handler->getCount();
        $nav         = '';
        if (0 < $regionCount) {
            // shows number of companies per page default = 15
            $showonpage = 50;
            $show       = '';
            $show       = ((int)$show > 0) ? (int)$show : $showonpage;

            $start = Request::getInt('start', 0, 'GET');
            if (!isset($max)) {
                $max = $start + $show;
            }

            $criteria = new \CriteriaCompo();
            $criteria->setSort('rid');
            $criteria->order = 'ASC';
            $criteria->setLimit($show);
            $criteria->setStart($start);
            $region_obj_array = $region_handler->getAll($criteria);

            echo "<table class='width100 bnone pad2' cellspacing='0'><td><tr>\n";
            $nav = new \XoopsPageNav($regionCount, $showonpage, $start, 'start', 'op=Region');
            //echo "<fieldset><legend class='bold' style='color: #900;'>" . _AM_JOBS_MAN_REGION . "</legend>";
            //echo "<br>" . _AM_JOBS_THEREIS . " <b>{$regionCount}</b> " . _AM_JOBS_REGIONS . "<br><br>";
            //echo "<fieldset><legend style='font-weight: bold; color:#900;'>"._AM_JOBS_ADD_REGION."</legend>";
            //echo "<br><a href='" . $helper->url('admin/addregion.php') . "'>" . _AM_JOBS_ADD_REGION . "</a>
            //<br><br><br><br><a href='" . $helper->url('admin/lists.php') . "'>" . _AM_JOBS_LISTS . "</a><br>";
            //echo "</td></tr>
            //</fieldset>";
            echo "<br>\n" . _AM_JOBS_THEREIS . " <b>{$regionCount}</b> " . _AM_JOBS_REGIONS . "<br><br>\n";
            //  echo "</td></tr></table>";
            echo $nav->renderNav();
            echo "<br><br><table class='width100 bnone pad2' cellspacing='0'>\n";
            $color = 'even';
            foreach($region_obj_array as $region_obj) {
                $color = ('even' == $color) ? 'odd' : 'even';
                echo "  <tr class='{$color}'>\n"
                   . "    <td><a href='" . $helper->url("admin/region.php?op=ModRegion&amp;rid=" . $region_obj->getVar('rid')) . "'>" . $region_obj->getVar('name') . "</a></td>\n"
                   . "  </tr>\n";
            }
            echo "</table><br>\n"
               . "</fieldset><br>\n"
               . $nav->renderNav();
        } else {
            $helper->redirect('admin/lists.php', 3, _AM_JOBS_NOREGION);
        }
        break;
}
xoops_cp_footer();
