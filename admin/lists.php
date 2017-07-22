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
//
require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

require_once XOOPS_ROOT_PATH . '/modules/jobs/admin/header.php';
xoops_cp_header();
//    loadModuleAdminMenu(3, "");

echo "<fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_LISTS . '</legend>';
echo '<br> ' . _AM_JOBS_INSTALL_NOW . '<br><br>';
echo '<a href="include/usstates.php">' . _AM_JOBS_US_STATES . '</a><br>';
echo '<a href="include/canada.php">' . _AM_JOBS_CANADA_STATES . '</a><br>';
echo '<a href="include/france.php">' . _AM_JOBS_FRANCE . '</a><br>';
echo '<a href="include/italy.php">' . _AM_JOBS_ITALY . '</a><br>';
echo '<a href="include/england.php">' . _AM_JOBS_ENGLAND . '</a><br>';
echo '</fieldset>';

xoops_cp_footer();
