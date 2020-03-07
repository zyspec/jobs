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
 * @author      John Mordo aka jlm69 (www.jlmzone.com )
 * @author      XOOPS Development Team
 * @link        https://github.com/XoopsModules25x/jobs
 */

$adminmenu = [
    [
        'title' => _MI_JOBS_HOME,
        'link'  => 'admin/index.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('home.png')
    ],
/*
    [
        'title' =>  _MI_JOBS_ADMENU2,
        'link'  =>  "admin/map.php",
        'icon'  => \Xmf\Module\Admin::menuIconPath('category.png')
    ],
*/
    [
        'title' => _MI_JOBS_ADMENU6,
        'link'  => 'admin/company.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('addlink.png')
    ],

    [
        'title' => _MI_JOBS_ADMENU8,
        'link'  => 'admin/jobs.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('cash_stack.png')
    ],

    [
        'title' => _MI_JOBS_ADMENU9,
        'link'  => 'admin/resumes.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('identity.png')
    ],
/*
    [
        'title' =>  _MI_JOBS_ADMENU1,
        'link'  =>  "admin/main.php",
        'icon'  => \Xmf\Module\Admin::menuIconPath('manage.png')
    ],
*/
    [
        'title' => _MI_JOBS_ADMENU1,
        'link'  => 'admin/jobs_type.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('manage.png')
    ],

    [
        'title' => _MI_JOBS_ADMENU3,
        'link'  => 'admin/groupperms.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('permissions.png')
    ],

    [
        'title' => _MI_JOBS_ADMENU7,
        'link'  => 'admin/region.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('languages.png')
    ],

    [
        'title' => _MI_JOBS_ABOUT,
        'link'  => 'admin/about.php',
        'icon'  => \Xmf\Module\Admin::menuIconPath('about.png')
    ]
];
