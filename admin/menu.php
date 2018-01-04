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

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Jobs\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$adminmenu[] = [
    'title' => _MI_JOBS_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

//$adminmenu[] = [
//'title' =>  _MI_JOBS_ADMENU2,
//'link' =>  "admin/map.php",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
//];

$adminmenu[] = [
    'title' => _MI_JOBS_ADMENU6,
    'link'  => 'admin/company.php',
    'icon'  => $pathIcon32 . '/addlink.png',
];

$adminmenu[] = [
    'title' => _MI_JOBS_ADMENU8,
    'link'  => 'admin/jobs.php',
    'icon'  => $pathIcon32 . '/cash_stack.png',
];

$adminmenu[] = [
    'title' => _MI_JOBS_ADMENU9,
    'link'  => 'admin/resumes.php',
    'icon'  => $pathIcon32 . '/identity.png',
];

//$adminmenu[] = [
//'title' =>  _MI_JOBS_ADMENU1,
//'link' =>  "admin/main.php",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
//];

$adminmenu[] = [
    'title' => _MI_JOBS_ADMENU1,
    'link'  => 'admin/jobs_type.php',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _MI_JOBS_ADMENU3,
    'link'  => 'admin/groupperms.php',
    'icon'  => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title' => _MI_JOBS_ADMENU7,
    'link'  => 'admin/region.php',
    'icon'  => $pathIcon32 . '/languages.png',
];

$adminmenu[] = [
    'title' => _MI_JOBS_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
