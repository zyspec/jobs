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
('67', '0', '---FRANCE---', 'FR'),
('68', '67', 'Alsace', 'Alsace'),
('69', '67', 'Aquitaine', 'Aquitaine'),
('70', '67', 'Auvergne', 'Auvergne'),
('71', '67', 'Bretagne', 'Bretagne'),
('72', '67', 'Bourgogne', 'Bourgogne'),
('73', '67', 'Centre', 'Centre'),
('74', '67', 'Champagne-Ardenne', 'Champagne-Ardenne'),
('75', '67', 'Corse', 'Corse'),
('76', '67', 'Franche-Comté', 'Franche-Comté'),
('77', '67', 'Languedoc-Roussillon', 'Languedoc-Roussillon'),
('78', '67', 'Limousin', 'Limousin'),
('79', '67', 'Lorraine', 'Lorraine'),
('80', '67', 'Basse-Normandie', 'Basse-Normandie'),
('81', '67', 'Midi-Pyrénées', 'Midi-Pyrénées'),
('82', '67', 'Nord-Pas-de-Calais', 'Nord-Pas-de-Calais'),
('83', '67', 'Île-de-France', 'Île-de-France'),
('84', '67', 'Pays-de-la-Loire', 'Pays-de-la-Loire'),
('85', '67', 'Picardie', 'Picardie'),
('86', '67', 'Poitou-Charentes', 'Poitou-Charentes'),
('87', '67', 'Provence-Alpes-Côte d\'Azur', 'Provence-Alpes-Côte d\'Azur'),
('88', '67', 'Rhône-Alpes', 'Rhône-Alpes'),
('89', '67', 'Haute-Normandie', 'Haute-Normandie')");

    if (!$xoopsDB->queryF($sql)) {
        $errors = $GLOBALS['xoopsDB']->error();

        redirect_header('../region.php', 3, _AM_JOBS_UPDATEFAILED . '
' . _AM_JOBS_ERROR . "$errors");

        exit();
    } else {
        redirect_header('../region.php', 3, _AM_JOBS_FRANCE_ADDED);
    }
} else {
    redirect_header('../../index.php', 3, _NO_PERM);
}

xoops_cp_footer();
