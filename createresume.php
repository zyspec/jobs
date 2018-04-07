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

use XoopsModules\Jobs;

$moduleDirName = basename(__DIR__);
include __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object
//require_once XOOPS_ROOT_PATH . "/class/module.errorhandler.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/resume_functions.php";
//$erh    = new ErrorHandler;
$mytree = new Jobs\JobTree($xoopsDB->prefix('xdir_cat'), 'cid', 'pid');
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

$module_id = $xoopsModule->getVar('mid');
if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
if (!$grouppermHandler->checkRight('jobs_submit', $perm_itemid, $groups, $module_id)) {
    //    redirect_header(XOOPS_URL."/user.php", 3, _NOPERM);
    redirect_header(XOOPS_URL . '/modules/jobs/resumes.php', 3, _NOPERM);
}
if (!$grouppermHandler->checkRight('jobs_premium', $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

if (!empty($_POST['lid'])) {
    $lid = \Xmf\Request::getInt('lid', 0, 'POST');
} else {
    $lid = \Xmf\Request::getInt('lid', 0, 'GET');
}

if (!empty($_POST['submit'])) {
    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . '/', 3, $GLOBALS['xoopsSecurity']->getErrors());
    }

    // Check if Title exist

    //    if ($_POST["resume"] == "") {
    //        $eh->show("1001");
    //    }

    $notify      = !empty($_POST['notify']) ? 1 : 0;
    $member_usid = $xoopsUser->getVar('uid', 'E');

    if ('dhtmltextarea' === $helper->getConfig('jobs_resume_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $made_resume = $myts->displayTarea($_POST['resume'], 0, 0, 0, 0, 0);
    } else {
        $made_resume = $myts->displayTarea($_POST['resume'], 1, 0, 1, 1, 1);
    }
    $date = time();

    $newid = $xoopsDB->genId($xoopsDB->prefix('jobs_created_resumes') . '_res_lid_seq');

    $sql = sprintf("INSERT INTO %s (res_lid, lid, made_resume, DATE, usid) VALUES (%u, '%s', '%s', '%s', '%s')", $xoopsDB->prefix('jobs_created_resumes'), $newid, $lid, $made_resume, $date, $member_usid);
    $xoopsDB->query($sql);

    $sql2 = 'UPDATE ' . $xoopsDB->prefix('jobs_resume') . " SET resume='created' where lid=" . $_POST['lid'] . '';
    $xoopsDB->query($sql2);

    redirect_header('viewresume.php?lid=' . addslashes($lid) . '', 3, _JOBS_RES_ADDED);
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'jobs_create_resume.tpl';
    include XOOPS_ROOT_PATH . '/header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $mytree = new Jobs\JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    $member_usid  = $xoopsUser->getVar('uid', 'E');
    $member_email = $xoopsUser->getVar('email', 'E');
    $member_uname = $xoopsUser->getVar('uname', 'E');

    ob_start();
    $form = new \XoopsThemeForm(_JOBS_CREATE_RESUME, 'createform', 'createresume.php');
    $form->setExtra('enctype="multipart/form-data"');

    //    $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');
    $form->addElement(resume_getEditor(_JOBS_RESUME, 'resume', '', 5, 40), true);
    $form->addElement(new \XoopsFormHidden('lid', $lid));
    $form->addElement(new \XoopsFormButton('', 'submit', _JOBS_SUBMIT, 'submit'));
    $form->display();
    $xoopsTpl->assign('submit_form', ob_get_contents());
    ob_end_clean();

    include XOOPS_ROOT_PATH . '/footer.php';
}
