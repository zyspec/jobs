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

use XoopsModules\Jobs;

require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
/**
 * @var \XoopsModules\Jobs\Helper $helper
 * @var \XoopsModules\Jobs\JobCategoryHandler $categoriesHandler
 * @var string $moduleDirName
 */
$cloned_lang   = '_MI_' . strtoupper($moduleDirName);

xoops_cp_header();
/** @var \Xmf\Module\Admin $adminObject */
$adminObject->displayNavigation(basename(__FILE__));

echo "<br><br>\n";
$cat_count = $categoriesHandler->getCount();
if (0 >= $cat_count) {
    echo constant($cloned_lang . '_MUST_ADD_CAT');
} else {
    $permtoset                = \Xmf\Request::getInt('permtoset', 1, 'POST');
    $selected                 = ['', '', '', '', ''];
    $selected[$permtoset - 1] = ' selected';
    echo "<form method='post' name='jselperm' action='groupperms.php'>\n"
       . "<table class='bnone'>\n"
       . "  <tr><td>\n"
       . "    <select name='permtoset' onChange= 'document.jselperm.submit()'>\n"
       . "      <option value='1'{$selected[0]}>" . constant($cloned_lang . '_VIEWFORM') . "</option>\n"
       . "      <option value='2'{$selected[1]}>" . constant($cloned_lang . '_SUBMITFORM') . "</option>\n"
       . "      <option value='3'{$selected[2]}>" . constant($cloned_lang . '_VIEW_RESUMEFORM') . "</option>\n"
       . "      <option value='4'{$selected[3]}>" . constant($cloned_lang . '_RESUMEFORM') . "</option>\n"
       . "      <option value='5'{$selected[4]}>" . constant($cloned_lang . '_PREMIUM') . "</option>\n"
       . "    </select>\n"
       . "  </td></tr>\n"
       . "</table>\n"
       . "</form>\n";

    $module_id = $helper->getModule()->mid();
    switch ($permtoset) {
        case 1:
            $title_of_form = constant($cloned_lang . '_VIEWFORM');
            $perm_name     = '' . $moduleDirName . '_view';
            $perm_desc     = constant($cloned_lang . '_VIEWFORM_DESC');
            $anonymous     = true;
            $permform      = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
            $cattree       = new Jobs\JobTree($GLOBALS['xoopsDB']->prefix('jobs_categories'), 'cid', 'pid');
            $allcats       = $cattree->getCategoryList();

            break;
        case 2:
            $title_of_form = constant($cloned_lang . '_SUBMITFORM');
            $perm_name     = '' . $moduleDirName . '_submit';
            $perm_desc     = constant($cloned_lang . '_SUBMITFORM_DESC');
            $anonymous     = false;
            $permform      = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
            $cattree       = new Jobs\JobTree($GLOBALS['xoopsDB']->prefix('jobs_categories'), 'cid', 'pid');
            $allcats       = $cattree->getCategoryList();

            break;
        case 3:
            $title_of_form = constant($cloned_lang . '_VIEW_RESUMEFORM');
            $perm_name     = 'resume_view';
            $perm_desc     = constant($cloned_lang . '_VIEW_RESUMEFORM_DESC');
            $anonymous     = true;
            $permform      = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
            $cattree       = new Jobs\JobTree($GLOBALS['xoopsDB']->prefix('jobs_res_categories'), 'cid', 'pid');
            $allcats       = $cattree->getCategoryList();

            break;
        case 4:
            $title_of_form = constant($cloned_lang . '_RESUMEFORM');
            $perm_name     = 'resume_submit';
            $perm_desc     = constant($cloned_lang . '_RESUMEFORM_DESC');
            $anonymous     = false;
            $permform      = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
            $cattree       = new Jobs\JobTree($GLOBALS['xoopsDB']->prefix('jobs_res_categories'), 'cid', 'pid');
            $allcats       = $cattree->getCategoryList();

            break;
        case 5:
            $title_of_form = constant($cloned_lang . '_PREMIUM');
            $perm_name     = '' . $moduleDirName . '_premium';
            $perm_desc     = constant($cloned_lang . '_PREMIUM_DESC');
            $anonymous     = false;
            $permform      = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
            $cattree       = new Jobs\JobTree($GLOBALS['xoopsDB']->prefix('jobs_categories'), 'cid', 'pid');
            $allcats       = $cattree->getCategoryList();

            break;
    }

    foreach ($allcats as $cid => $category) {
        $permform->addItem($cid, $category['title'], $category['pid']);
    }
    echo $permform->render();
    echo "<br><br><br><br>\n";
    unset($permform);
}

xoops_cp_footer();
