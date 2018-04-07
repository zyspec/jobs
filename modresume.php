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

include __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('resume_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/resumes.php", 3, _NOPERM);
}

if (!empty($_POST['submit'])) {
    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/resumes.php", 3, $GLOBALS['xoopsSecurity']->getErrors());
    }
    $resumesize  = $helper->getConfig('jobs_resumesize');
    $resumesize1 = $helper->getConfig('jobs_resumesize') / 1024;
    $destination = XOOPS_ROOT_PATH . "/modules/$moduleDirName/resumes";

    $lid             = !isset($_REQUEST['lid']) ? null : $_REQUEST['lid'];
    $del_old         = !isset($_REQUEST['del_old']) ? null : $_REQUEST['del_old'];
    $del_made_resume = !isset($_REQUEST['del_made_resume']) ? null : $_REQUEST['del_made_resume'];
    $resume_old      = !isset($_REQUEST['resume_old']) ? null : $_REQUEST['resume_old'];
    $make_resume     = !isset($_REQUEST['make_resume']) ? null : $_REQUEST['make_resume'];

    if (1 == $del_old) {
        if (file_exists("$destination/$resume_old")) {
            unlink("$destination/$resume_old");
        }
        $resume_old = '';
    }
    if (1 == $del_made_resume) {
        $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('jobs_created_resumes') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
        $resume_old = '';
    }

    $cid       = $myts->addSlashes($_POST['cid']);
    $name      = $myts->addSlashes($_POST['name']);
    $title     = $myts->addSlashes($_POST['title']);
    $status    = $myts->addSlashes($_POST['status']);
    $exp       = $myts->addSlashes($_POST['exp']);
    $expire    = $myts->addSlashes($_POST['expire']);
    $private   = $myts->addSlashes($_POST['private']);
    $tel       = $myts->addSlashes($_POST['tel']);
    $salary    = $myts->addSlashes($_POST['salary']);
    $typeprice = $myts->addSlashes($_POST['typeprice']);
    $date      = $myts->addSlashes($_POST['date']);
    $email     = $myts->addSlashes($_POST['email']);
    $submitter = $myts->addSlashes($_POST['submitter']);
    $town      = $myts->addSlashes($_POST['town']);
    $state     = $myts->addSlashes($_POST['state']);
    $valid     = $myts->addSlashes($_POST['valid']);

    if (!empty($_FILES['resume']['name'])) {
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $updir             = $destination;
        $allowed_mimetypes = ['application/msword', 'application/pdf'];
        $uploader          = new \XoopsMediaUploader($updir, $allowed_mimetypes, $helper->getConfig('jobs_resumesize'));
        $uploader->setTargetFileName($date . '_' . $_FILES['resume']['name']);
        $uploader->fetchMedia('resume');
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();
            redirect_header('modresume.php?lid=' . addslashes($lid) . '', 5, $errors);
        } else {
            if ($resume_old) {
                if (@file_exists("$destination/$resume_old")) {
                    unlink("$destination/$resume_old");
                }
            }
            $resume_old = $uploader->getSavedFileName();
        }
    }

    $xoopsDB->query('update '
                    . $xoopsDB->prefix('jobs_resume')
                    . " set cid='$cid', name='$name', title='$title', status='$status', exp='$exp', expire='$expire', private='$private', tel='$tel', salary='$salary', typeprice='$typeprice', date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', resume='$resume_old' where lid=$lid");

    if ($make_resume) {
        redirect_header('createresume.php?lid=' . addslashes($lid) . '', 4, _JOBS_RES_UP_PLUS);
    } else {
        redirect_header('viewresume.php?lid=' . addslashes($lid) . '', 4, _JOBS_RES_MOD);
    }
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'jobs_modresume.tpl';
    include XOOPS_ROOT_PATH . '/header.php';
    require_once XOOPS_ROOT_PATH . '/modules/jobs/include/resume_functions.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $mytree = new Jobs\ResumeTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');

    $lid = \Xmf\Request::getInt('lid', 0, 'GET');

    $resumesize  = $helper->getConfig('jobs_resumesize');
    $resumesize1 = $helper->getConfig('jobs_resumesize') / 1024;

    $result = $xoopsDB->query('SELECT lid, cid, name, title, status, exp, expire, private, tel, salary, typeprice, date, email, submitter, usid, town, state, valid, resume FROM ' . $xoopsDB->prefix('jobs_resume') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
    list($lid, $cid, $name, $title, $status, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $resume_old) = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->uid();
        if ($usid == $calusern) {
            $name      = $myts->undoHtmlSpecialChars($name);
            $title     = $myts->undoHtmlSpecialChars($title);
            $status    = $myts->htmlSpecialChars($status);
            $exp       = $myts->htmlSpecialChars($exp);
            $expire    = $myts->htmlSpecialChars($expire);
            $private   = $myts->htmlSpecialChars($private);
            $tel       = $myts->htmlSpecialChars($tel);
            $salary    = $myts->htmlSpecialChars($salary);
            $typeprice = $myts->htmlSpecialChars($typeprice);
            $email     = $myts->htmlSpecialChars($email);
            $submitter = $myts->htmlSpecialChars($submitter);
            $town      = $myts->htmlSpecialChars($town);
            $state     = $myts->htmlSpecialChars($state);

            $useroffset = '';
            $timezone   = $xoopsUser->timezone();
            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }

            $dates   = ($useroffset * 3600) + $date;
            $dates   = formatTimestamp($date, 's');
            $del_old = '';

            $result1 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY id_price');
            $result2 = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid');

            ob_start();
            $form = new \XoopsThemeForm(_JOBS_MOD_LISTING, 'modify_resume', 'modresume.php');
            $form->setExtra('enctype="multipart/form-data"');
            //            $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');

            $form->addElement(new \XoopsFormLabel(_JOBS_NUMANNN, $lid . ' ' . _JOBS_ADDED . ' ' . $dates));
            $form->addElement(new \XoopsFormLabel(_JOBS_SENDBY, $submitter));
            $form->addElement(new \XoopsFormText(_JOBS_RES_PCODE . ' - ' . _JOBS_RES_PSIZE, 'private', 5, 10, $private), false);

            $cat_form = new \XoopsFormSelect(_JOBS_CAT, 'cid', $cid);
            $cattree  = $mytree->resume_getChildTreeArray(0, 'title ASC');
            $cat_form->addOption('', _JOBS_SELECTCAT);
            foreach ($cattree as $branch) {
                $branch['prefix'] = substr($branch['prefix'], 0, -1);
                $branch['prefix'] = str_replace('.', '--', $branch['prefix']);
                $cat_form->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
            }
            $form->addElement($cat_form, true);

            $form->addElement(new \XoopsFormText(_JOBS_NAME, 'name', 30, 30, $name), false);
            $form->addElement(new \XoopsFormText(_JOBS_TITLE2, 'title', 30, 30, $title), false);
            $form->addElement(new \XoopsFormText(_JOBS_RES_EXP, 'exp', 30, 30, $exp), false);
            $form->addElement(new \XoopsFormText(_JOBS_RES_HOW_LONG, 'expire', 30, 30, $expire), false);

            $radio        = new \XoopsFormRadio(_JOBS_STATUS, 'status', $status);
            $options['1'] = _JOBS_ACTIVE;
            $options['0'] = _JOBS_INACTIVE;
            $radio->addOptionArray($options);
            $form->addElement($radio, true);

            $form->addElement(new \XoopsFormText(_JOBS_EMAIL, 'email', 30, 30, $email), false);
            $form->addElement(new \XoopsFormText(_JOBS_TEL, 'tel', 30, 30, $tel), false);
            $form->addElement(new \XoopsFormText(_JOBS_TOWN, 'town', 30, 30, $town), false);

            $state_form = new \XoopsFormSelect(_JOBS_STATE, 'state', $state, '1', false);
            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result2))) {
                $state_form->addOption('', _JOBS_SELECT_STATE);
                $state_form->addOption($rid, $name);
            }
            $form->addElement($state_form, true);

            $form->addElement(new \XoopsFormText(_JOBS_PRICE2, 'salary', 40, 50, $salary), false);
            $sel_form = new \XoopsFormSelect(_JOBS_SALARYTYPE, 'typeprice', $typeprice, '1', false);
            while (false !== (list($nom_price) = $xoopsDB->fetchRow($result1))) {
                $sel_form->addOption($nom_price, $nom_price);
            }
            $form->addElement($sel_form);

            if ($resume_old) {
                if ('created' !== $resume_old) {
                    $resume_link = "<a href=\"resumes/$resume_old\">$resume_old</a>";

                    $form->addElement(new \XoopsFormLabel(_JOBS_ACTUALRES, $resume_link));

                    $del_checkbox = new \XoopsFormCheckBox(_JOBS_DELRES, 'del_old', '');
                    $del_checkbox->addOption(1, 'Yes');
                    $form->addElement($del_checkbox);
                    $form->addElement(new \XoopsFormFile(_JOBS_NEWRES, 'resume', $helper->getConfig('jobs_maxfilesize')), false);
                    $form->addElement(new \XoopsFormHidden('resume_old', $resume_old));
                } else {
                    $resume_link = '<a href="myresume.php?lid=' . addslashes($lid) . "\">$resume_old</a>";
                    $form->addElement(new \XoopsFormLabel(_JOBS_ACTUALRES, $resume_link));

                    $del_made_checkbox = new \XoopsFormCheckBox(_JOBS_DELRES, 'del_made_resume', '');
                    $del_made_checkbox->addOption(1, 'Yes');
                    $form->addElement($del_made_checkbox);
                    $form->addElement(new \XoopsFormHidden('resume_old', $resume_old));
                }
            } else {
                $form->addElement(new \XoopsFormFile(_JOBS_NEWRES, 'resume', $helper->getConfig('jobs_maxfilesize')), false);
            }

            $res_radio    = new \XoopsFormRadio(_JOBS_Q_NO_RESUME, 'make_resume', '0');
            $options['0'] = _JOBS_DONT_MAKE;
            $options['1'] = _JOBS_MAKE_RESUME;
            $res_radio->addOptionArray($options);
            $form->addElement($res_radio, true);

            if (0 == $helper->getConfig('jobs_moderate_res_up')) {
                $form->addElement(new \XoopsFormHidden('valid', '1'), false);
            } else {
                $form->addElement(new \XoopsFormHidden('valid', '0'), false);
            }

            $form->addElement(new \XoopsFormHidden('lid', $lid), false);
            $form->addElement(new \XoopsFormHidden('date', $date), false);
            $form->addElement(new \XoopsFormHidden('submit', '1'), false);
            $form->addElement(new \XoopsFormHidden('submitter', $submitter), false);
            $form->addElement(new \XoopsFormButton('', 'submit', _JOBS_SUBMIT, 'submit'));
            $form->display();
            $xoopsTpl->assign('modify_form', ob_get_contents());
            ob_end_clean();
        }
    }
}
include XOOPS_ROOT_PATH . '/footer.php';
