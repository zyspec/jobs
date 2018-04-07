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
('1', '0', '---UNITED STATES---', 'US'),
('2', '1', 'Alabama', 'AL'),
('3',' 1', 'Alaska', 'AK'),
('4', '1', 'Arizona', 'AZ'),
('5', '1', 'Arkansas', 'AR'),
('6', '1', 'California', 'CA'),
('7', '1', 'Colorado', 'CO'),
('8', '1', 'Connecticut', 'CT'),
('9', '1', 'Delaware', 'DE'),
('10', '1', 'District of Columbia', 'DC'),
('11', '1', 'Florida', 'FL'),
('12', '1', 'Georgia', 'GA'),
('13', '1', 'Hawaii', 'HI'),
('14', '1', 'Idaho', 'ID'),
('15', '1', 'Illinois', 'IL'),
('16', '1', 'Indiana', 'IN'),
('17', '1', 'Iowa', 'IA'),
('18', '1', 'Kansas', 'KS'),
('19', '1', 'Kentucky', 'KY'),
('20', '1', 'Louisiana', 'LA'),
('21', '1', 'Maine', 'ME'),
('22', '1', 'Maryland', 'MD'),
('23', '1', 'Massachusetts', 'MA'),
('24', '1', 'Michigan', 'MI'),
('25', '1', 'Minnesota', 'MN'),
('26', '1', 'Mississippi', 'MS'),
('27', '1', 'Missouri', 'MO'),
('28', '1', 'Montana', 'MT'),
('29', '1', 'Nebraska', 'NE'),
('30', '1', 'Nevada', 'NV'),
('31', '1', 'New Hampshire', 'NH'),
('32', '1', 'New Jersey', 'NJ'),
('33', '1', 'New Mexico', 'NM'),
('34', '1', 'New York', 'NY'),
('35', '1', 'North Carolina', 'NC'),
('36', '1', 'North Dakota', 'ND'),
('37', '1', 'Ohio', 'OH'),
('38', '1', 'Oklahoma', 'OK'),
('39', '1', 'Oregon', 'OR'),
('40', '1', 'Pennsylvania', 'PA'),
('41', '1', 'Rhode Island', 'RI'),
('42', '1', 'South Carolina', 'SC'),
('43', '1', 'South Dakota', 'SD'),
('44', '1', 'Tennessee', 'TN'),
('45', '1', 'Texas', 'TX'),
('46', '1', 'Utah', 'UT'),
('47', '1', 'Vermont', 'VT'),
('48', '1', 'Virginia', 'VA'),
('49', '1', 'Washington', 'WA'),
('50', '1', 'West Virginia', 'WV'),
('51', '1', 'Wisconsin', 'WI'),
('52', '1', 'Wyoming', 'WY')");

    if (!$xoopsDB->queryF($sql)) {
        $errors = $GLOBALS['xoopsDB']->error();
        redirect_header('../region.php', 3, _AM_JOBS_UPDATEFAILED . '
    ' . _AM_JOBS_ERROR . (string)$errors);
    } else {
        redirect_header('../region.php', 3, _AM_JOBS_US_ADDED);
    }
} else {
    redirect_header('../../index.php', 3, _AM_JOBS_NO_PERM);
}

xoops_cp_footer();
