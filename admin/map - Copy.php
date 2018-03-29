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

use XoopsModules\Jobs;
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

//include("admin_header.php");
require_once __DIR__ . '/../../../include/cp_header.php';

$moduleDirName = basename(dirname(__DIR__));

//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/restree.php";
$mytree  = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');
$restree = new JobTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');

global $mytree, $restree, $xoopsDB,  $moduleDirName;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
//    loadModuleAdminMenu(1, "");
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_JOBS_ADDSUBCAT, 'addregion.php', 'add', '');
$adminObject->addItemButton(_AM_JOBS_ADDCATPRINC, 'lists.php', 'list', '');
$adminObject->displayButton('left', '');

echo "<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_CATEGORY . '</legend>';
echo '<br><a href="category.php?op=NewCat&amp;cid=0"><img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/plus.gif\" border=0 width=10 height=10  alt=\"" . _AM_JOBS_ADDSUBCAT . '"></a> ' . _AM_JOBS_ADDCATPRINC . '<br><br>';

$mytree->makeJobSelBox('title', '' . $helper->getConfig('jobs_cat_sortorder') . '');

echo '<br><hr>';
echo '<p>' . _AM_JOBS_HELP1 . ' </p>';

if ('ordre' === $helper->getConfig('jobs_cat_sortorder')) {
    echo '<p>' . _AM_JOBS_HELP2 . ' </p>';
}
echo '<br></fieldset><br>';
echo "<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_RES_CATEGORY . '</legend>';
echo '<br><a href="category.php?op=NewResCat&amp;cid=0"><img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/plus.gif\" border=0 width=10 height=10  alt=\"" . _AM_JOBS_ADDSUBCAT . '"></a> ' . _AM_JOBS_ADDCATPRINC . '<br><br>';

$restree->makeResSelBox('title', '' . $helper->getConfig('jobs_cat_sortorder') . '');
echo '<br></fieldset><br>';

xoops_cp_footer();
