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

use XoopsModules\Jobs\DirectoryChecker;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

/**
 * Vars defined through inclusion of ./admin_header.php
 *
 * @var \Xmf\Module\Admin $adminObject
 * @var \XoopsModules\Jobs\Helper $helper
 */

//-----------------------
/*
* jobs
 * validate
 * published
 * total jobs
 * Categories

* resumes
 * validate
 * published
 * total jobs
 * Categories

 * payment type
 * job type
 *
* Companies
*
* */

$summary = jobs_summary();

$adminObject->addInfoBox(_AM_JOBS_SUMMARY);
//$adminObject->addInfoBoxLine(_AM_JOBS_SUMMARY,   "------ JOBS -----------------",  'Red');
$adminObject->addInfoBoxLine(sprintf(_AM_JOBS_WAITVA_JOB, $summary['waitJobValidation']), 'Green');
$adminObject->addInfoBoxLine(sprintf(_AM_JOBS_PUBLISHED, $summary['jobPublished']), 'Red');
$adminObject->addInfoBoxLine(sprintf(_AM_JOBS_CATETOT, $summary['jobCategoryCount']), 'Green');

//$adminObject->addInfoBoxLine(   "</br>  "."------ RESUMES -----------------",  'Red');
$adminObject->addInfoBoxLine('</br>  ' . sprintf(_AM_JOBS_WAITVA_RESUME, $summary['waitResumeValidation']), 'Green');
//$adminObject->addInfoBoxLine( "<b>"._AM_JOBS_VIEWSCAP  ."</b>  ". sprintf(_AM_JOBS_VIEWS, $summary['views']),  'Green');
$adminObject->addInfoBoxLine(sprintf(_AM_JOBS_RESUME_PUBLISHED, $summary['resumePublished']), 'Green');
$adminObject->addInfoBoxLine(sprintf(_AM_JOBS_RESUME_CAT_TOTAL, $summary['resumeCategoryCount']), 'Green');

//$adminObject->addInfoBoxLine(   "</br>  "."------ COMPANIES -----------------",  'Red');
//$adminObject->addInfoBoxLine(  "</br>  "."<b>"._AM_JOBS_COMPANY_TOTCAP ."</b>  ". sprintf(_AM_JOBS_WAITVA_RESUME,$summary['waitResumeValidation']),  'Green');
$adminObject->addInfoBoxLine('</br>  ' . sprintf(_AM_JOBS_COMPANY_TOT, $summary['companies']), 'Green');

//------ check directories ---------------

$adminObject->addConfigBoxLine('');

$languageConstants = [
    _AM_JOBS_AVAILABLE,
    _AM_JOBS_NOTAVAILABLE,
    _AM_JOBS_CREATETHEDIR,
    _AM_JOBS_NOTWRITABLE,
    _AM_JOBS_SETMPERM,
    _AM_JOBS_DIRCREATED,
    _AM_JOBS_DIRNOTCREATED,
    _AM_JOBS_PERMSET,
    _AM_JOBS_PERMNOTSET
];

$pathArray = [
    [
        'dir'    => $helper->path('photo'),
        'perm'   => 0755,
        'action' => $_SERVER['SCRIPT_NAME']
    ],

    [
        'dir'    => $helper->path('photo/thumbs'),
        'perm'   => 0755,
        'action' => $_SERVER['SCRIPT_NAME']
    ],

    [
        'dir'    => $helper->path('photo/midsize'),
        'perm'   => 0755,
        'action' => $_SERVER['SCRIPT_NAME']
    ],

    [
        'dir'    => $helper->path('resumes'),
        'perm'   => 0755,
        'action' => $_SERVER['SCRIPT_NAME']
    ],

    [
        'dir'    => $helper->path('rphoto'),
        'perm'   => 0755,
        'action' => $_SERVER['SCRIPT_NAME']
    ]
];

foreach ($pathArray as $path) {
    $adminObject->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path['dir'], $path['perm'], $languageConstants, $path['action']));
}

//---------------------------

xoops_loadLanguage('admin/modulesadmin', 'system');

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_SYSTEM_MODULES_INSTALL_TESTDATA, '__DIR__ . /../../testdata/index.php?op=load', 'add');
$adminObject->displayButton('left');
$adminObject->displayIndex();

jobs_filechecks();

require_once __DIR__ . '/admin_footer.php';
