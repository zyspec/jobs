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
 * @author      John Mordo
 * @author      XOOPS Development Team
 */

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/include/cp_header.php';
xoops_cp_header();
$moduleDirName = $xoopsModule->getVar('dirname');
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/functions.php';

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;

    $sql = sprintf('INSERT INTO ' . $xoopsDB->prefix('jobs_region') . " (rid, pid, name, abbrev) VALUES
('109', '0', '---England---', 'ENG'),
('110', '109', 'East Midlands', ''),
('111', '109', 'East of England', ''),
('112', '109', 'Greater London', ''),
('113', '109', 'North East England', ''),
('114', '109', 'North West England', ''),
('115', '109', 'South East England', ''),
('116', '109', 'South West England', ''),
('117', '109', 'West Midlands', ''),
('118', '109', 'Yorkshire and the Humber', '')");

    if (!$xoopsDB->queryF($sql)) {
        $errors = $GLOBALS['xoopsDB']->error();

        redirect_header('../region.php', 3, _AM_JOBS_UPDATEFAILED . '
' . _AM_JOBS_ERROR . (string)$errors);

        exit();
    } else {
        redirect_header('../region.php', 3, _AM_JOBS_ENGLAND_ADDED);
    }
} else {
    redirect_header('../../index.php', 3, _NO_PERM);
}

xoops_cp_footer();
