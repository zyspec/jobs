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

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}


$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminmenu              = [];
$i                      = 0;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
//++$i;
//$adminmenu[$i]['title'] = _MI_JOBS_ADMENU2;
//$adminmenu[$i]['link']  = "admin/map.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU6;
$adminmenu[$i]['link']  = 'admin/company.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/addlink.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU8;
$adminmenu[$i]['link']  = 'admin/jobs.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/cash_stack.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU9;
$adminmenu[$i]['link']  = 'admin/resumes.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/identity.png';
//++$i;
//$adminmenu[$i]['title'] = _MI_JOBS_ADMENU1;
//$adminmenu[$i]['link']  = "admin/main.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU1;
$adminmenu[$i]['link']  = 'admin/jobs_type.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/manage.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU3;
$adminmenu[$i]['link']  = 'admin/groupperms.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/permissions.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU7;
$adminmenu[$i]['link']  = 'admin/region.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/languages.png';
++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
