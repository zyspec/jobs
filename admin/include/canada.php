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
('53', '0', '---CANADA---', 'CA'),
('54', '53', 'Alberta', 'AB'),
('55',' 53', 'British Columbia', 'BC'),
('56', '53', 'Manitoba', 'MB'),
('57', '53', 'New Brunswick', 'NB'),
('58', '53', 'Newfoundland and Labrador', 'NL'),
('59', '53', 'Northwest Territories', 'NT'),
('60', '53', 'Nova Scotia', 'NS'),
('61', '53', 'Nunavut', 'NU'),
('62', '53', 'Ontario', 'ON'),
('63', '53', 'Prince Edward Island', 'PE'),
('64', '53', 'Québec', 'QC'),
('65', '53', 'Saskatchewan', 'SK'),
('66', '53', 'Yukon', 'YT')");

    if (!$xoopsDB->queryF($sql)) {
        $errors = $GLOBALS['xoopsDB']->error();

        redirect_header('../region.php', 2, _AM_JOBS_UPDATEFAILED . '
' . _AM_JOBS_ERROR . (string)$errors);

        exit();
    } else {
        redirect_header('../region.php', 2, _AM_JOBS_CANADA_ADDED);
    }
} else {
    redirect_header('../../index.php', 2, _AM_JOBS_NO_PERM);
}

xoops_cp_footer();
