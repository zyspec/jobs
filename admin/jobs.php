<?php
/*
 * Jobs for XOOPS
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * @package     \XoopsModules\Jobs
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author      John Mordo
 * @author      XOOPS Development Team
 * @link        https://github.com/XoopsModules25x/jobs
 */

use Xmf\Request;
use XoopsModules\Jobs;

require_once __DIR__ . '/admin_header.php';

xoops_load('XoopsPageNav');

/**
 * @var \XoopsModules\Jobs\Helper $helper
 * @var \MyTextSanitizer $myts
 * @var \Xmf\Module\Admin $adminObject
 */
$helper = Jobs\Helper::getInstance();

xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_JOBS_ADD_LINK, 'submitlisting.php', 'add');
$adminObject->displayButton('left');

$listing_handler = $helper->getHandler('Listing');
//include XOOPS_ROOT_PATH . '/class/pagenav.php';

$listing_count = $listing_handler->getCount();

$nav = '';
if (0 < $listing_count) {
    // shows number of jobs per page set in preferences
    $showonpage = $helper->getConfig('jobs_joblisting_num');
    $show       = Request::getInt('show', $showonpage);
    $start      = Request::getInt('start', 0);
    $max        = Request::getInt('max', $start + $show);

    echo "<table class='width100 pad2 bnone' cellspacing='0'>\n"
       . "  <tr><td>\n";

    $criteria = new \CriteriaCompo();
    $criteria->setSort('valid ASC, lid');  // trick criteria to allow 2 sort criteria
    $criteria->order = 'ASC';
    $criteria->setLimit($show);
    $criteria->setStart($start);
    $listing_object_array = $listing_handler->getAll($criteria);
    $listing_count = count($listing_object_array);
    if (0 < $listing_count) {
        $nav = new \XoopsPageNav($listing_count, $showonpage, $start, 'start');
        echo "    <br>\n"
           . "    " . _AM_JOBS_THEREIS . " <b>{$listing_count}</b> " . _AM_JOBS_JOBLISTINGS . "<br><br>" . $nav->renderNav() . "\n"
           . "    <br><br>\n";
    }

    echo "      <table class='width100 outer' cellspacing='1'>\n"
       . "        <thead>\n"
       . "        <tr>\n"
       . "          <th class='center'>" . _AM_JOBS_NUMANN . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_TITLE2 . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_SUBMITTED_ON . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_ACTIVE . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_EXPIRES . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_SENDBY . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_PUBLISHEDCAP . "</th>\n"
       . "          <th class='center'>" . _AM_JOBS_PREMIUM . "</th>\n"
       . "          <th class='center width10'>" . _AM_JOBS_ACTIONS . "</th>\n"
       . "        </tr>\n"
       . "        </thead>\n"
       . "        <tbody>\n";

    $class   = 'even';

    foreach ($listing_object_array as $lid => $listing_object) {
        $class   = ('even' === $class) ? 'odd' : 'even';
        $date2   = formatTimestamp($listing_object->getVar('date', 's'));
        //$expire2 = formatTimestamp($Listing_object->getVar('expire'), "s");

        echo "        <tr class='{$class}'>"
           . "          <td class='center'>{$lid}</td>\n"
           . "          <td class='center'>" . $listing_object->getVar('title') . "</td>\n"
           . "          <td class='center'>{$date2}</td>\n"
           . "          <td class='center'>" . $listing_object->getVar('status') . "</td>\n"
           . "          <td class='center'>" . $listing_object->getVar('expire') . "</td>\n"
           . "          <td class='center'>" . $listing_object->getVar('submitter') . "</td>\n"
           . "          <td class='center'>" . $listing_object->getVar('valid') . "</td>\n"
           . "          <td class='center'>" . $listing_object->getVar('premium') . "</td>\n"
           . "          <td class='center width10'>\n"
           . "            <a href='modjobs.php?lid={$lid}'><img src='" . \Xmf\Module\Admin::iconUrl('edit.png', '16') . "' alt='" . _EDIT . "' title='" . _EDIT . "'></a>\n"
           . "            <a href='../deljob.php?lid={$lid}'><img src='" . \Xmf\Module\Admin::iconUrl('delete.png', '16') . "' alt='" . _DELETE . "' title='" . _DELETE . "'></a>\n"
           . "          </td>\n"
           . "        </tr>\n";
    }
    echo "        </tbody>\n"
       . "      </table>\n<br><br>\n" . $nav->renderNav() . "\n";
} else {
    echo "<fieldset>\n"
       . "  <legend class='bold' style='color: #900;'>" . _AM_JOBS_MAN_JOB . "</legend>\n"
       . "  <br>\n " . _AM_JOBS_NO_JOB . "<br><br>\n"
       . "</fieldset>\n";
}

require_once __DIR__ . '/job_categories.php';
