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
 * @author      XOOPS Development Team
 */

use XoopsModules\Jobs;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

//  jlm69
require_once XOOPS_ROOT_PATH . '/modules/jobs/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/jobs/include/resume_functions.php';

/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

$mytree       = new Jobs\JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');
$restree      = new ResumeTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');
$staterestree = new ResumeTree($xoopsDB->prefix('jobs_region'), 'rid', 'pid');
$statetree    = new Jobs\JobTree($xoopsDB->prefix('jobs_region'), 'rid', 'pid');
$xmid         = $xoopsModule->getVar('mid');

if (!empty($_GET['is_resume'])) {
    $is_resume = \Xmf\Request::getInt('is_resume', 0, 'GET');
} elseif (!empty($_POST['is_resume'])) {
    $is_resume = \Xmf\Request::getInt('is_resume', 0, 'POST');
} else {
    $is_resume = '';
}
// end jlm69
// create form
$search_form = new \XoopsThemeForm(_SR_SEARCH, 'search', 'search.php', 'get');

// create form elements
$search_form->addElement(new \XoopsFormText(_SR_KEYWORDS, 'query', 30, 255, htmlspecialchars(stripslashes(implode(' ', $queries)), ENT_QUOTES)), true);
$type_select = new \XoopsFormSelect(_SR_TYPE, 'andor', $andor);
$type_select->addOptionArray(['AND' => _SR_ALL, 'OR' => _SR_ANY, 'exact' => _SR_EXACT]);
$search_form->addElement($type_select);
//  jlm69
if (!empty($is_resume)) {
    ob_start();
    $restree->resume_makeMySearchSelBox('title', 'title', $by_cat, '1', 'by_cat');
    $search_form->addElement(new \XoopsFormLabel(_JOBS_CAT, ob_get_contents()));
    ob_end_clean();

    if ('1' == $helper->getConfig('jobs_show_state')) {
        if ('1' == $helper->getConfig('jobs_countries')) {
            ob_start();
            $staterestree->resume_makeMyStateSelBox('name', 'rid', $by_state, '1', 'by_state');
            $search_form->addElement(new \XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        } else {
            ob_start();
            $staterestree->resume_makeStateSelBox('name', 'rid', $by_state, '1', 'by_state');
            $search_form->addElement(new \XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        }
    } else {
        $search_form->addElement(new \XoopsFormHidden('state', ''));
    }
} else {
    ob_start();
    $mytree->makeMySearchSelBox('title', 'title', $by_cat, '1', 'by_cat');
    $search_form->addElement(new \XoopsFormLabel(_JOBS_CAT, ob_get_contents()));
    ob_end_clean();

    if ('1' == $helper->getConfig('jobs_show_state')) {
        if ('1' == $helper->getConfig('jobs_countries')) {
            ob_start();
            $statetree->makeMyStateSelBox('name', 'rid', $by_state, '1', 'by_state');
            $search_form->addElement(new \XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        } else {
            $statetree->makeStateSelBox('name', 'rid', $by_state, '1', 'by_state');
            $search_form->addElement(new \XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        }
    } else {
        $search_form->addElement(new \XoopsFormHidden('state', ''));
    }
}

//  if (!empty($mids)) {
//  $mods_checkbox = new \XoopsFormCheckBox(_SR_SEARCHIN, "mids[]", $mids);
//  } else {
$mods_checkbox = new \XoopsFormCheckBox(_SR_SEARCHIN, 'mids[]', $mid);
//  }
//end jlm69
if (empty($modules)) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('hassearch', 1));
    $criteria->add(new \Criteria('isactive', 1));
    if (!empty($available_modules)) {
        $criteria->add(new \Criteria('mid', $xmid));
    }
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $mods_checkbox->addOptionArray($moduleHandler->getList($criteria));
} else {
    foreach ($modules as $mid => $module) {
        $module_array[$mid] = $module->getVar('name');
    }
    $mods_checkbox->addOptionArray($module_array);
}
//jlm69
//  $search_form->addElement($mods_checkbox);
//$search_form->addElement(new \XoopsFormHidden("mods_checkbox","array(mods_checkbox => $mods_checkbox)"));
// end jlm69
if ($xoopsConfigSearch['keyword_min'] > 0) {
    $search_form->addElement(new \XoopsFormLabel(_SR_SEARCHRULE, sprintf(_SR_KEYIGNORE, $xoopsConfigSearch['keyword_min'])));
}

$search_form->addElement(new \XoopsFormHidden('issearch', '1'));
$search_form->addElement(new \XoopsFormHidden('is_resume', $is_resume));
$search_form->addElement(new \XoopsFormHidden('action', 'results'));
$search_form->addElement(new \XoopsFormHiddenToken('id'));
$search_form->addElement(new \XoopsFormButton('', 'submit', _SR_SEARCH, 'submit'));
