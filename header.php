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

use XoopsModules\Jobs;

include dirname(dirname(__DIR__)) . '/mainfile.php';
$myts = \MyTextSanitizer::getInstance();

$moduleDirName = basename(dirname(__DIR__));
$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);

$helper = Jobs\Helper::getInstance();