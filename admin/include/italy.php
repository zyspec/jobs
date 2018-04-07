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
('90', '0', '---Italy---', 'IT'),
('91', '90', 'Abruzzo', 'Abruzzo'),
('92', '90', 'Lombardy', 'Lombardy'),
('93', '90', 'Amalfi Coast', 'Amalfi Coast'),
('94', '90', 'Marche', 'Marche'),
('95', '90', 'Aosta', 'Aosta'),
('96', '90', 'Molise', 'Molise'),
('97', '90', 'Basilicata', 'Basilicata'),
('98', '90', 'Piemonte', 'Piemonte'),
('99', '90', 'Calabria', 'Calabria'),
('100', '90', 'Puglia - Apulia', 'Puglia - Apulia'),
('101', '90', 'Campania', 'Campania'),
('102', '90', 'Trentino - Alto Adige', 'Trentino - Alto Adige'),
('103', '90', 'Emilia Romagna', 'Emilia Romagna'),
('104', '90', 'Tuscany', 'Tuscany'),
('105', '90', 'Umbria', 'Umbria'),
('106', '90', 'Lazio', 'Lazio'),
('107', '90', 'Veneto', 'Veneto'),
('108', '90', 'Liguria', 'Liguria')");

    if (!$xoopsDB->queryF($sql)) {
        $errors = $GLOBALS['xoopsDB']->error();

        redirect_header('../region.php', 3, _AM_JOBS_UPDATEFAILED . '
' . _AM_JOBS_ERROR . (string)$errors);

        exit();
    } else {
        redirect_header('../region.php', 3, _AM_JOBS_ITALY_ADDED);
    }
} else {
    redirect_header('../../index.php', 3, _AM_JOBS_NO_PERM);
}

xoops_cp_footer();
