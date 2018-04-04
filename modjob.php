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
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

include __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$grouppermHandler = xoops_getHandler('groupperm');
if (isset($_POST['item_id'])) {
    $perm_itemid = (int)$_POST['item_id'];
} else {
    $perm_itemid = 0;
}
//If no access
if (!$grouppermHandler->checkRight('jobs_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/index.php", 3, _NOPERM);
}
if (!$grouppermHandler->checkRight('jobs_premium', $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";

if (!empty($_POST['submit'])) {
    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/index.php", 3, $GLOBALS['xoopsSecurity']->getErrors());
    }

    $lid     = $myts->addSlashes($_POST['lid']);
    $cid     = $myts->addSlashes($_POST['cid']);
    $title   = $myts->addSlashes($_POST['title']);
    $status  = $myts->addSlashes($_POST['status']);
    $expire  = $myts->addSlashes($_POST['expire']);
    $type    = $myts->addSlashes($_POST['type']);
    $company = $myts->undoHtmlSpecialChars($_POST['company']);

    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $desctext = $myts->displayTarea($_POST['desctext'], 0, 0, 1, 1, 0);
    } else {
        $desctext = $myts->displayTarea($_POST['desctext'], 1, 0, 1, 1, 1);
    }

    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $requirements = $myts->displayTarea($_POST['requirements'], 0, 0, 1, 1, 0);
    } else {
        $requirements = $myts->displayTarea($_POST['requirements'], 1, 0, 1, 1, 1);
    }

    $tel          = $myts->addSlashes($_POST['tel']);
    $price        = $myts->addSlashes($_POST['price']);
    $typeprice    = $myts->addSlashes($_POST['typeprice']);
    $contactinfo  = $myts->displayTarea($_POST['contactinfo'], 0, 0, 0, 0, 0);
    $contactinfo1 = $myts->addSlashes($_POST['contactinfo1']);
    $contactinfo2 = $myts->addSlashes($_POST['contactinfo2']);
    $date         = $myts->addSlashes($_POST['date']);
    $email        = $myts->addSlashes($_POST['email']);
    $submitter    = $myts->addSlashes($_POST['submitter']);
    $town         = $myts->addSlashes($_POST['town']);
    $state        = $myts->addSlashes($_POST['state']);
    $valid        = $myts->addSlashes($_POST['valid']);
    $photo        = $myts->addSlashes($_POST['photo']);

    $xoopsDB->query('update '
                    . $xoopsDB->prefix('jobs_listing')
                    . " set cid='$cid', title='$title', status='$status', expire='$expire', type='$type', company='$company', desctext='$desctext', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice',  contactinfo='$contactinfo',  contactinfo1='$contactinfo1',  contactinfo2='$contactinfo2', date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', photo='$photo' where lid="
                    . $xoopsDB->escape($lid)
                    . '');

    if ('1' == $valid) {
        redirect_header('viewjobs.php?lid=' . addslashes($lid) . '', 3, _JOBS_JOBMOD2);
    } else {
        redirect_header('index.php', 3, _JOBS_MODIFBEFORE);
    }
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'jobs_modify.tpl';
    include XOOPS_ROOT_PATH . '/header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
    $mytree = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    $photomax1 = $helper->getConfig('jobs_maxfilesize') / 1024;
    $lid       = ((int)$_GET['lid']);

    $result = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, usid, town, state, valid, photo FROM '
                              . $xoopsDB->prefix('jobs_listing')
                              . ' WHERE lid='
                              . $xoopsDB->escape($lid)
                              . '');
    list($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $usid, $town, $state, $valid, $photo) = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $comp_id     = jobs_getCompIdFromName(addslashes($company));
        $thiscompany = jobs_getCompany($usid);
        if ($thiscompany) {
            $member_usid = $xoopsUser->uid();
            $extra_users = jobs_getXtraUsers($comp_id, $member_usid);
            if (!empty($extra_users)) {
                $temp_premium = '1';
            } else {
                $temp_premium = '0';
            }

            $title   = $myts->undoHtmlSpecialChars($title);
            $status  = $myts->htmlSpecialChars($status);
            $expire  = $myts->htmlSpecialChars($expire);
            $type    = $myts->htmlSpecialChars($type);
            $company = $myts->undoHtmlSpecialChars($company);

            if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
                || 'dhtml' === $helper->getConfig('jobs_form_options')) {
                $desctext = $myts->undoHtmlSpecialChars($myts->displayTarea($desctext, 0, 0, 1, 1, 0));
            } else {
                $desctext = $myts->displayTarea($desctext, 1, 0, 1, 1, 1);
            }

            if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
                || 'dhtml' === $helper->getConfig('jobs_form_options')) {
                $requirements = $myts->undoHtmlSpecialChars($myts->displayTarea($requirements, 0, 0, 1, 1, 0));
            } else {
                $requirements = $myts->displayTarea($requirements, 1, 0, 1, 1, 1);
            }

            $tel          = $myts->htmlSpecialChars($tel);
            $price        = $myts->htmlSpecialChars($price);
            $typeprice    = $myts->htmlSpecialChars($typeprice);
            $contactinfo  = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo, 0, 0, 0, 0, 0));
            $contactinfo1 = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo1, 0, 0, 0, 0, 0));
            $contactinfo2 = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo2, 0, 0, 0, 0, 0));
            $submitter    = $myts->htmlSpecialChars($submitter);
            $town         = $myts->htmlSpecialChars($town);
            $state        = $myts->htmlSpecialChars($state);

            $useroffset = '';
            if ($xoopsUser) {
                $timezone = $xoopsUser->timezone();
                if (isset($timezone)) {
                    $useroffset = $xoopsUser->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }
            $dates = ($useroffset * 3600) + $date;
            $dates = formatTimestamp($date, 's');

            $result  = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('jobs_type') . ' ORDER BY nom_type');
            $result1 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY id_price');
            $result2 = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid ASC');
            ob_start();
            $form = new \XoopsThemeForm(_JOBS_MOD_LISTING, 'modifyform', 'modjob.php');
            $form->setExtra('enctype="multipart/form-data"');
            //            $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');

            $form->addElement(new \XoopsFormLabel(_JOBS_NUMANNN, $lid . ' ' . _JOBS_ADDED . ' ' . $dates));
            $form->addElement(new \XoopsFormLabel(_JOBS_SENDBY, $submitter));
            if ('1' == $helper->getConfig('jobs_show_company')) {
                $form->addElement(new \XoopsFormLabel(_JOBS_COMPANY2, $company));
                $form->addElement(new \XoopsFormHidden('company', $company));
            } else {
                $form->addElement(new \XoopsFormHidden('company', $company));
            }
            $form->addElement(new \XoopsFormText(_JOBS_EMAIL, 'email', 30, 100, $email), true);
            $form->addElement(new \XoopsFormText(_JOBS_TEL, 'tel', 30, 30, $tel), false);
            $form->addElement(new \XoopsFormText(_JOBS_TOWN, 'town', 50, 50, $town), false);

            if ('1' == $helper->getConfig('jobs_show_state')) {
                $state_form = new \XoopsFormSelect(_JOBS_STATE, 'state', $state, '0', false);
                while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result2))) {
                    $state_form->addOption('', _JOBS_SELECT_STATE);
                    $state_form->addOption($rid, $name);
                }
                $form->addElement($state_form, true);
            } else {
                $form->addElement(new \XoopsFormHidden('state', ''));
            }

            if (('1' == $premium) || ('1' == $temp_premium)) {
                $radio        = new \XoopsFormRadio(_JOBS_STATUS, 'status', $status);
                $options['1'] = _JOBS_ACTIVE;
                $options['0'] = _JOBS_INACTIVE;
                $radio->addOptionArray($options);
                $form->addElement($radio, true);
            } else {
                $form->addElement(new \XoopsFormHidden('status', '1'));
            }
            $form->addElement(new \XoopsFormText(_JOBS_TITLE, 'title', 40, 50, $title), true);
            if (('1' == $premium) || ('1' == $temp_premium)) {
                $form->addElement(new \XoopsFormText(_JOBS_HOW_LONG, 'expire', 3, 3, $helper->getConfig('jobs_days')), true);
            } else {
                $form->addElement(new \XoopsFormLabel(_JOBS_NON_HOW_LONG, $helper->getConfig('jobs_days')));
                $form->addElement(new \XoopsFormHidden('expire', $helper->getConfig('jobs_days')));
            }
            $type_form = new \XoopsFormSelect(_JOBS_JOB_TYPE, 'type', $type, '1', false);
            while (false !== (list($nom_type) = $xoopsDB->fetchRow($result))) {
                $type_form->addOption($nom_type, $nom_type);
            }
            $form->addElement($type_form);

            $cat_form = new \XoopsFormSelect(_JOBS_CAT, 'cid', $cid);
            $cattree  = $mytree->getChildTreeArray(0, 'title ASC');
            $cat_form->addOption('', _JOBS_SELECTCAT);
            foreach ($cattree as $branch) {
                $branch['prefix'] = substr($branch['prefix'], 0, -1);
                $branch['prefix'] = str_replace('.', '--', $branch['prefix']);
                $cat_form->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
            }
            $form->addElement($cat_form, true);

            $form->addElement(jobs_getEditor(_JOBS_DESC, 'desctext', $desctext, '100%', '300px', ''), true);
            $form->addElement(jobs_getEditor(_JOBS_REQUIRE, 'requirements', $requirements, '100%', '300px', ''), true);
            $form->addElement(new \XoopsFormText(_JOBS_PRICE2, 'price', 40, 50, $price), false);
            $sel_form = new \XoopsFormSelect(_JOBS_SALARYTYPE, 'typeprice', $typeprice, '1', false);
            while (false !== (list($nom_price) = $xoopsDB->fetchRow($result1))) {
                $sel_form->addOption($nom_price, $nom_price);
            }
            $form->addElement($sel_form);

            $form->addElement(new \XoopsFormTextArea(_JOBS_CONTACTINFO, 'contactinfo', $contactinfo, 6, 40), false);

            if (('1' == $premium) || ('1' == $temp_premium)) {
                $form->addElement(new \XoopsFormTextArea(_JOBS_CONTACTINFO1, 'contactinfo1', $contactinfo1, 6, 40), false);
                $form->addElement(new \XoopsFormTextArea(_JOBS_CONTACTINFO2, 'contactinfo2', $contactinfo2, 6, 40), false);
            } else {
                $form->addElement(new \XoopsFormHidden('contactinfo1', ''));
                $form->addElement(new \XoopsFormHidden('contactinfo2', ''));
            }

            if (0 == $helper->getConfig('jobs_moderated')) {
                $form->addElement(new \XoopsFormHidden('valid', '1'), false);
            } else {
                $form->addElement(new \XoopsFormHidden('valid', '0'), false);
            }

            $form->addElement(new \XoopsFormHidden('lid', $lid), false);
            $form->addElement(new \XoopsFormHidden('photo', $photo), false);
            $form->addElement(new \XoopsFormHidden('date', $date), false);
            $form->addElement(new \XoopsFormHidden('submitter', $submitter), false);
            $form->addElement(new \XoopsFormButton('', 'submit', _JOBS_SUBMIT, 'submit'));
            $form->display();
            $xoopsTpl->assign('modify_form', ob_get_contents());
            ob_end_clean();
        }
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
