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


require_once __DIR__ . '/admin_header.php';
$moduleDirName = basename(dirname(__DIR__));
$myts          = \MyTextSanitizer::getInstance(); // MyTextSanitizer object
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

$module_id = $xoopsModule->getVar('mid');
if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
if (!$grouppermHandler->checkRight('jobs_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}
if (!$grouppermHandler->checkRight('jobs_premium', $perm_itemid, $groups, $module_id)) {
    $premium = '0';
} else {
    $premium = '1';
}
$mytree      = new Jobs\JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');
$companytree = new Jobs\JobTree($xoopsDB->prefix('jobs_companies'), 'comp_id', 'comp_pid');

$cid    = \Xmf\Request::getInt('cid', 0);
$comp_id    = \Xmf\Request::getInt('comp_id', '');

$member_usid = $xoopsUser->getVar('uid', 'E');

if (!empty($_POST['submit'])) {
    $jobsdays = $helper->getConfig('jobs_days');

    $title   = $myts->addSlashes($_POST['title']);
    $status  = $myts->addSlashes($_POST['status']);
    $expire  = $myts->addSlashes($_POST['expire']);
    $type    = $myts->addSlashes($_POST['type']);
    $company = $myts->addSlashes($_POST['company']);
    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')) {
        $desctext = $myts->displayTarea($_POST['desctext'], 0, 0, 0, 0, 0);
    } else {
        $desctext = $myts->displayTarea($_POST['desctext'], 1, 1, 1, 1, 1);
    }
    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')) {
        $requirements = $myts->displayTarea($_POST['requirements'], 0, 0, 0, 0, 0);
    } else {
        $requirements = $myts->displayTarea($_POST['requirements'], 1, 1, 1, 1, 1);
    }

    $tel          = $myts->addSlashes($_POST['tel']);
    $price        = $myts->addSlashes($_POST['price']);
    $typeprice    = $myts->addSlashes($_POST['typeprice']);
    $contactinfo  = $myts->addSlashes($_POST['contactinfo']);
    $contactinfo1 = $myts->addSlashes($_POST['contactinfo1']);
    $contactinfo2 = $myts->addSlashes($_POST['contactinfo2']);
    $submitter    = $myts->addSlashes($_POST['submitter']);
    $usid         = $myts->addSlashes($member_usid);
    $town         = $myts->addSlashes($_POST['town']);
    $state        = $myts->addSlashes($_POST['state']);
    $valid        = $myts->addSlashes($_POST['valid']);
    $email        = $myts->addSlashes($_POST['email']);
    $view         = 0;
    $photo        = '';
    $date         = time();

    $newid = $xoopsDB->genId($xoopsDB->prefix('jobs_listing') . '_lid_seq');

    $sql = sprintf('INSERT INTO '
                   . $xoopsDB->prefix('jobs_listing')
                   . " (lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, usid, town, state, valid, photo, view) VALUES ('$newid', '$cid', '$title', '$status', '$expire', '$type', '$company', '$desctext', '$requirements', '$tel', '$price', '$typeprice', '$contactinfo', '$contactinfo1', '$contactinfo2', '$date', '$email', '$submitter', '$usid', '$town', '$state', '$valid', '$photo', '$view')");
    $xoopsDB->query($sql);

    if ('1' == $valid) {
        $notificationHandler     = xoops_getHandler('notification');
        $lid                     = $xoopsDB->getInsertId();
        $tags                    = [];
        $tags['LID']             = $lid;
        $tags['TITLE']           = $title;
        $tags['TYPE']            = $type;
        $tags['DESCTEXT']        = $desctext;
        $tags['HELLO']           = _AM_JOBS_HELLO;
        $tags['ADDED_TO_CAT']    = _AM_JOBS_ADDED_TO_CAT;
        $tags['FOLLOW_LINK']     = _AM_JOBS_FOLLOW_LINK;
        $tags['RECIEVING_NOTIF'] = _AM_JOBS_RECIEVING_NOTIF;
        $tags['ERROR_NOTIF']     = _AM_JOBS_ERROR_NOTIF;
        $tags['WEBMASTER']       = _AM_JOBS_WEBMASTER;
        $tags['LINK_URL']        = XOOPS_URL . '/modules/' . $moduleDirName . '/viewjobs.php' . '?lid=' . addslashes($lid);
        $sql                     = 'SELECT title FROM ' . $xoopsDB->prefix('jobs_categories') . ' WHERE cid=' . addslashes($cid);
        $result                  = $xoopsDB->query($sql);
        $row                     = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_TITLE']  = $row['title'];
        $tags['CATEGORY_URL']    = XOOPS_URL . '/modules/' . $moduleDirName . '/jobscat.php?cid="' . addslashes($cid);
        $notificationHandler     = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global', 0, 'new_job', $tags);
        $notificationHandler->triggerEvent('category', $cid, 'new_job_cat', $tags);
        $notificationHandler->triggerEvent('listing', $lid, 'new_job', $tags);
    }
    redirect_header('jobs.php', 3, _AM_JOBS_JOBADDED);
} elseif (empty($_POST['select']) && ('1' == $helper->getConfig('jobs_show_company'))) {
    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('jobs.php');
    $adminObject->addItemButton(_AM_JOBS_MAN_JOB, 'jobs.php', 'list');
    $adminObject->displayButton('left', '');

    $iscompany = jobs_getAllCompanies();
    if (!$iscompany) {
        redirect_header('addcomp.php', 3, _AM_JOBS_MUSTADD_COMPANY);
    }

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    ob_start();
    $form = new \XoopsThemeForm(_AM_JOBS_SELECTCOMPADD, 'select_form', 'submitlisting.php');
    $form->setExtra('enctype="multipart/form-data"');

    ob_start();
    $companytree->makeMyAdminCompBox('comp_name', 'comp_name', '', 0, 'comp_id');
    $form->addElement(new \XoopsFormLabel(_AM_JOBS_SELECTCOMPANY, ob_get_contents()), true);
    ob_end_clean();

    $form->addElement(new \XoopsFormButton('', 'select', _AM_JOBS_CONTINUE, 'submit'));
    $form->display();
    $select_form = ob_get_contents();
    ob_end_clean();
    echo $select_form;

    //    xoops_cp_footer();
    require_once __DIR__ . '/admin_footer.php';
} else {
    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('jobs.php');
    $adminObject->addItemButton(_AM_JOBS_MAN_JOB, 'jobs.php', 'list');
    $adminObject->displayButton('left', '');

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $mytree = new Jobs\JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    $cid    = \Xmf\Request::getInt('cid', 0, 'POST');


    $comp_id    = \Xmf\Request::getInt('comp_id', '');

    $member_usid  = $xoopsUser->uid();
    $member_email = $xoopsUser->getVar('email', 'E');
    $member_uname = $xoopsUser->getVar('uname', 'E');
    $email        = $member_email;

    if ('1' == $helper->getConfig('jobs_show_company')) {
        $company = jobs_getACompany($comp_id);
    }
    $result  = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid');
    $result1 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('jobs_type') . ' ORDER BY nom_type');
    $result2 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY id_price');
    ob_start();
    $form = new \XoopsThemeForm(_AM_JOBS_ADD_LISTING, 'submitform', 'submitlisting.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new \XoopsFormLabel(_AM_JOBS_SUBMITTER, $member_uname));
    $form->addElement(new \XoopsFormHidden('submitter', $member_uname));
    $form->addElement(new \XoopsFormText(_AM_JOBS_EMAIL, 'email', 50, 100, $email), true);
    if ('1' == $helper->getConfig('jobs_show_company')) {
        if ('' == $comp_id) {
            $form->addElement(new \XoopsFormText(_AM_JOBS_COMPANY, 'company', 50, 50, '', true));
        } else {
            $form->addElement(new \XoopsFormLabel(_AM_JOBS_COMPANY, $company['comp_name']));
            $form->addElement(new \XoopsFormHidden('company', $company['comp_name']));
        }

        $form->addElement(new \XoopsFormText(_AM_JOBS_TOWN, 'town', 50, 50, $company['comp_city']), false);

        if ('1' == $helper->getConfig('jobs_show_state')) {
            $state_form = new \XoopsFormSelect(_AM_JOBS_STATE, 'state', $company['comp_state'], '0', false);
            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result))) {
                $state_form->addOption('', _AM_JOBS_SELECT_STATE);
                $state_form->addOption($rid, $name);
            }
            $form->addElement($state_form, true);
        } else {
            $form->addElement(new \XoopsFormHidden('state', ''));
        }

        $form->addElement(new \XoopsFormText(_AM_JOBS_TEL, 'tel', 30, 30, $company['comp_phone']), false);

        $sel_cat = new \XoopsFormSelect(_AM_JOBS_CAT, 'cid', '');
        $cattree = $mytree->getChildTreeArray(0, 'title ASC');
        $sel_cat->addOption('', _AM_JOBS_SELECTCAT);
        foreach ($cattree as $branch) {
            $branch['prefix'] = substr($branch['prefix'], 0, -1);
            $branch['prefix'] = str_replace('.', '--', $branch['prefix']);
            $sel_cat->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
        }
        $form->addElement($sel_cat, true);

        $form->addElement(new \XoopsFormText(_AM_JOBS_HOW_LONG, 'expire', 3, 3, $helper->getConfig('jobs_days')), true);

        $type_form = new \XoopsFormSelect(_AM_JOBS_TYPE, 'type', '', '0', false);
        while (false !== (list($nom_type) = $xoopsDB->fetchRow($result1))) {
            $type_form->addOption($nom_type, $nom_type);
        }
        $form->addElement($type_form);

        $radio        = new \XoopsFormRadio(_AM_JOBS_STATUS, 'status', 1);
        $options['1'] = _AM_JOBS_ACTIVE;
        $options['0'] = _AM_JOBS_INACTIVE;
        $radio->addOptionArray($options);
        $form->addElement($radio, true);
        $form->addElement(new \XoopsFormText(_AM_JOBS_TITLE, 'title', 40, 50, ''), true);
        $form->addElement(jobs_getEditor(_AM_JOBS_DESC, 'desctext', '', '100%', '300px', ''), true);
        $form->addElement(jobs_getEditor(_AM_JOBS_REQUIRE, 'requirements', '', '100%', '300px', ''), true);
        $form->addElement(new \XoopsFormText(_AM_JOBS_PRICE2, 'price', 40, 50, ''), false);
        $sel_form = new \XoopsFormSelect(_AM_JOBS_SALARYTYPE, 'typeprice', '', '1', false);
        while (false !== (list($nom_price) = $xoopsDB->fetchRow($result2))) {
            $sel_form->addOption($nom_price, $nom_price);
        }
        $form->addElement($sel_form);
        $form->addElement(new \XoopsFormText(_AM_JOBS_EMAIL, 'email', 50, 100, $email), true);
        $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO, 'contactinfo', '' . $company['comp_contact'] . '', 6, 40), false);
        if ($company['comp_user1_contact']) {
            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO1, 'contactinfo1', '' . $company['comp_user1_contact'] . '', 6, 40), false);
        } else {
            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO1, 'contactinfo1', '', 6, 40), false);
        }
        if ($company['comp_user2_contact']) {
            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO2, 'contactinfo2', '' . $company['comp_user2_contact'] . '', 6, 40), false);
        } else {
            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO2, 'contactinfo2', '', 6, 40), false);
        }
        $form->addElement(new \XoopsFormHidden('valid', '1'), false);
        $form->addElement(new \XoopsFormHidden('comp_id', $company['comp_id']), false);
        $form->addElement(new \XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
        $form->display();
        $submit_form = ob_get_contents();
        ob_end_clean();
        echo $submit_form;
    } else {
        $form->addElement(new \XoopsFormText(_AM_JOBS_TOWN, 'town', 50, 50, ''), false);
        if ('1' == $helper->getConfig('jobs_show_state')) {
            $state_form = new \XoopsFormSelect(_AM_JOBS_STATE, 'state', '', '0', false);
            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result))) {
                $state_form->addOption('', _AM_JOBS_SELECT_STATE);
                $state_form->addOption($rid, $name);
            }
            $form->addElement($state_form, true);
        } else {
            $form->addElement(new \XoopsFormHidden('state', ''));
        }

        $form->addElement(new \XoopsFormText(_AM_JOBS_TEL, 'tel', 30, 30, ''), false);
        ob_start();
        $mytree->makeMyAdminSelBox('title', 'title', '', 'cid');
        $form->addElement(new \XoopsFormLabel(_AM_JOBS_CAT, ob_get_contents()), true);
        ob_end_clean();
        $form->addElement(new \XoopsFormText(_AM_JOBS_HOW_LONG, 'expire', 3, 3, $helper->getConfig('jobs_days')), true);
        $type_form = new \XoopsFormSelect(_AM_JOBS_TYPE, 'type', '', '1', false);
        while (false !== (list($nom_type) = $xoopsDB->fetchRow($result1))) {
            $type_form->addOption($nom_type, $nom_type);
        }
        $form->addElement($type_form);
        $radio        = new \XoopsFormRadio(_AM_JOBS_STATUS, 'status', 1);
        $options['1'] = _AM_JOBS_ACTIVE;
        $options['0'] = _AM_JOBS_INACTIVE;
        $radio->addOptionArray($options);
        $form->addElement($radio, true);
        $form->addElement(new \XoopsFormText(_AM_JOBS_TITLE, 'title', 40, 50, ''), true);
        $form->addElement(jobs_getEditor(_AM_JOBS_DESC, 'desctext', '', '100%', '300px', ''), true);
        $form->addElement(jobs_getEditor(_AM_JOBS_REQUIRE, 'requirements', '', '100%', '300px', ''), true);
        $form->addElement(new \XoopsFormText(_AM_JOBS_PRICE2, 'price', 40, 50, ''), false);
        $sel_form = new \XoopsFormSelect(_AM_JOBS_SALARYTYPE, 'typeprice', '', '1', false);
        while (false !== (list($nom_price) = $xoopsDB->fetchRow($result2))) {
            $sel_form->addOption($nom_price, $nom_price);
        }
        $form->addElement($sel_form);
        $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO, 'contactinfo', '', 6, 40), false);
        $form->addElement(new \XoopsFormHidden('valid', '1'), false);
        $form->addElement(new \XoopsFormHidden('comp_id', ''), false);
        $form->addElement(new \XoopsFormHidden('company', ''), false);
        $form->addElement(new \XoopsFormHidden('contactinfo1', ''), false);
        $form->addElement(new \XoopsFormHidden('contactinfo2', ''), false);
        $form->addElement(new \XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
        $form->display();
        $submit_form = ob_get_contents();
        ob_end_clean();
        echo $submit_form;
    }
}
require_once __DIR__ . '/admin_footer.php';
//xoops_cp_footer();
