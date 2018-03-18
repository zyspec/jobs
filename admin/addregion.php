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
//
require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$myts = \MyTextSanitizer::getInstance();

if (!empty($_POST['submit'])) {
    $name   = $myts->addSlashes($_POST['name']);
    $pid    = $myts->addSlashes($_POST['pid']);
    $abbrev = $myts->addSlashes($_POST['abbrev']);

    $newid = $xoopsDB->genId($xoopsDB->prefix('jobs_region') . '_rid_seq');

    $sql = sprintf('INSERT INTO ' . $xoopsDB->prefix('jobs_region') . " (rid, pid, name, abbrev) VALUES ('$newid', '$pid', '$name', '$abbrev')");
    $xoopsDB->query($sql);

    redirect_header('region.php', 4, _AM_JOBS_REGION_ADDED);
} else {
    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(4, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('region.php');

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    ob_start();
    $form = new \XoopsThemeForm(_AM_JOBS_ADD_REGION, 'regionform', 'addregion.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new \XoopsFormText(_AM_JOBS_REGION_NAME, 'name', 20, 50, ''), true);
    $form->addElement(new \XoopsFormText(_AM_JOBS_REGION_ABBREV, 'abbrev', 2, 4, ''), false);
    $form->addElement(new \XoopsFormButton('', 'submit', _AM_JOBS_ADDREGION, 'submit'));
    $form->addElement(new \XoopsFormHidden('pid', '0'));
    $form->display();
    $submit_form = ob_get_contents();
    ob_end_clean();
    echo $submit_form;
}

xoops_cp_footer();
