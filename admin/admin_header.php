<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * @package      \XoopsModules\Jobs
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @link         https://github.com/XoopsModules25x/jobs
 */

use XoopsModules\Jobs;

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');
require_once dirname(__DIR__) . '/include/functions.php';
require_once dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));
/**
 * @var \XoopsModules\Jobs\Helper $helper
 * @var \Xmf\Module\Admin $adminObject
 */
$helper      = Jobs\Helper::getInstance();
$adminObject = \Xmf\Module\Admin::getInstance();

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

if (!$helper->isUserAdmin()) {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}

$myts = \MyTextSanitizer::getInstance();

if (!$GLOBALS['xoopsTpl'] instanceof \XoopsTpl) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

//load handlers
$priceHandler          = Jobs\Helper::getInstance()->getHandler('Price');
$typeHandler           = Jobs\Helper::getInstance()->getHandler('JobType');
$categoriesHandler     = Jobs\Helper::getInstance()->getHandler('JobCategory');
$resumeCategoryHandler = Jobs\Helper::getInstance()->getHandler('ResumeCategory');
