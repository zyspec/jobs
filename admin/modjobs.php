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

require_once __DIR__ . '/admin_header.php';

require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (!empty($_POST['submit'])) {
    $lid = !isset($_REQUEST['lid']) ? null : $_REQUEST['lid'];

    $cid          = $myts->addSlashes($_POST['cid']);
    $title        = $myts->addSlashes($_POST['title']);
    $status       = $myts->addSlashes($_POST['status']);
    $expire       = $myts->addSlashes($_POST['expire']);
    $type         = $myts->addSlashes($_POST['type']);
    $company      = $myts->addSlashes($_POST['company']);
    $desctext     = $myts->addSlashes($_POST['desctext']);
    $requirements = $myts->addSlashes($_POST['requirements']);
    $tel          = $myts->addSlashes($_POST['tel']);
    $price        = $myts->addSlashes($_POST['price']);
    $typeprice    = $myts->addSlashes($_POST['typeprice']);
    $contactinfo  = $myts->addSlashes($_POST['contactinfo']);
    $contactinfo1 = $myts->addSlashes($_POST['contactinfo1']);
    $contactinfo2 = $myts->addSlashes($_POST['contactinfo2']);
    $date         = $myts->addSlashes($_POST['date']);
    $email        = $myts->addSlashes($_POST['email']);
    $submitter    = $myts->addSlashes($_POST['submitter']);
    //    $usid         = $myts->addSlashes($_POST["usid"]);
    $town    = $myts->addSlashes($_POST['town']);
    $state   = $myts->addSlashes($_POST['state']);
    $valid   = $myts->addSlashes($_POST['valid']);
    $premium = $myts->addSlashes($_POST['premium']);
    $photo   = $myts->addSlashes($_POST['photo']);
    $view    = $myts->addSlashes($_POST['view']);

    //    if (!empty($_FILES['resume']['name'])) {
    //        require_once XOOPS_ROOT_PATH . "/class/uploader.php";
    //        $updir             = $destination;
    //        $allowed_mimetypes = array('application/msword', 'application/pdf');
    //        $uploader          = new \XoopsMediaUploader($updir, $allowed_mimetypes, $helper->getConfig('jobs_resumesize'));
    //        $uploader->setTargetFileName($date . '_' . $_FILES['resume']['name']);
    //        $uploader->fetchMedia('resume');
    //        if (!$uploader->upload()) {
    //            $errors = $uploader->getErrors();
    //            redirect_header("modresume.php?lid=" . addslashes($lid) . "", 5, $errors);
    //
    //        } else {
    //            if ($resume_old) {
    //                if (@file_exists("$destination/$resume_old")) {
    //                    unlink("$destination/$resume_old");
    //                }
    //            }
    //            $resume_old = $uploader->getSavedFileName();
    //        }
    //    }

    //    $xoopsDB->query(
    //        "update " . $xoopsDB->prefix("jobs_listing")
    //            . " set cid='$cid', title='$title', status='$status', expire='$expire',  type='$type', company='$company',desctext='$desctext',requirements='$requirements',tel='$tel', price='$price', typeprice='$typeprice', contactinfo='$contactinfo',contactinfo1='$contactinfo1',contactinfo2='$contactinfo2',date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', premium='$premium',photo='$photo',view='$view' where lid=$lid"
    //    );

    //$lid = (int)($_GET['lid']);

    //UPDATE  `xtest256a2`.`xcac_jobs_listing` SET  `title` =  'zzzzzzzzzzz',
    //`desctext` =  'zzzzzzzz',
    //`requirements` =  'zzzzzzzzzzzzzzzz',
    //`price` =  'zzzzzzzzzzzzz',
    //`contactinfo1` =  'zzzzzzzzzzz',
    //`contactinfo2` =  'zzzzzzzzzzzz',
    //`photo` =  'zzzzzzzzzzz' WHERE  `xcac_jobs_listing`.`lid` =3;

    $myquery = $xoopsDB->query('update '
                               . $xoopsDB->prefix('jobs_listing')
                               . " set cid='$cid', title='$title', status='$status', expire='$expire', type='$type', company='$company', desctext='$desctext', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice',  contactinfo='$contactinfo',  contactinfo1='$contactinfo1',  contactinfo2='$contactinfo2', date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', premium='$premium', photo='$photo' where lid="
                               . $xoopsDB->escape($lid)
                               . '');

    if ($myquery) {
        redirect_header('jobs.php', 3, _JOBS_JOBMOD2);
    } else {
        redirect_header('jobs.php', 3, _JOBS_MODIFBEFORE);
    }

    //    if ($valid == "Yes") {
    //        redirect_header("jobs.php", 3, _JOBS_JOBMOD2);
    //
    //    } else {
    //        redirect_header("jobs.php", 3, _JOBS_MODIFBEFORE);
    //
    //    }
} else {
    xoops_cp_header();
    //loadModuleAdminMenu(3, "");

    //$adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('jobs.php');
    //$adminObject->addItemButton(_AM_JOBS_NEWCATEGORY, 'resume_categories.php?op=new_category', 'add');
    $adminObject->addItemButton(_AM_JOBS_MAN_JOB, 'jobs.php', 'list');
    $adminObject->displayButton('left', '');

    require_once XOOPS_ROOT_PATH . '/modules/jobs/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
    $mytree = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    $GLOBALS['xoopsSecurity']->getTokenHTML();

    $lid = \Xmf\Request::getInt('lid', 0, 'GET');

    //    $resumesize  = $helper->getConfig('jobs_resumesize');
    //    $resumesize1 = $helper->getConfig('jobs_resumesize') / 1024;

    $result = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, town, state, valid, premium, photo, view FROM '
                              . $xoopsDB->prefix('jobs_listing')
                              . ' WHERE lid='
                              . $xoopsDB->escape($lid)
                              . '');

    list($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $town, $state, $valid, $premium, $photo, $view) = $xoopsDB->fetchRow($result);

    //list($lid, $cid, $name, $title, $status, $exp, $expire, $private, $tel, $typeprice, $salary, $date, $email, $submitter, $usid, $town, $state, $valid, $resume_old) = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusername = $xoopsUser->uname();
        if ($submitter == $calusername || $xoopsUser->isAdmin()) {
            $lid          = $myts->htmlSpecialChars($lid);
            $cid          = $myts->htmlSpecialChars($cid);
            $title        = $myts->undoHtmlSpecialChars($title);
            $status       = $myts->htmlSpecialChars($status);
            $expire       = $myts->htmlSpecialChars($expire);
            $type         = $myts->htmlSpecialChars($type);
            $company      = $myts->htmlSpecialChars($company);
            $desctext     = $myts->htmlSpecialChars($desctext);
            $requirements = $myts->htmlSpecialChars($requirements);
            $tel          = $myts->htmlSpecialChars($tel);
            $price        = $myts->htmlSpecialChars($price);
            $typeprice    = $myts->htmlSpecialChars($typeprice);
            $contactinfo  = $myts->htmlSpecialChars($contactinfo);
            $contactinfo1 = $myts->htmlSpecialChars($contactinfo1);
            $contactinfo2 = $myts->htmlSpecialChars($contactinfo2);
            $date         = $myts->htmlSpecialChars($date);
            $email        = $myts->htmlSpecialChars($email);
            $submitter    = $myts->htmlSpecialChars($submitter);
            //            $usid         = $myts->htmlSpecialChars($usid);
            $town    = $myts->htmlSpecialChars($town);
            $state   = $myts->htmlSpecialChars($state);
            $valid   = $myts->htmlSpecialChars($valid);
            $premium = $myts->htmlSpecialChars($premium);
            $photo   = $myts->htmlSpecialChars($photo);
            $view    = $myts->htmlSpecialChars($view);

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

            ob_start();
            $form = new \XoopsThemeForm(_AM_JOBS_MODANN, 'modify_jobs', 'modjobs.php');
            $form->setExtra('enctype="multipart/form-data"');
            //            $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');

            $form->addElement(new \XoopsFormLabel(_AM_JOBS_NUMANN, $lid . ' ' . _AM_JOBS_ADDED . ' ' . $dates));
            $form->addElement(new \XoopsFormLabel(_AM_JOBS_SENDBY, $submitter));

            $form->addElement(new \XoopsFormText(_AM_JOBS_EMAIL, 'email', 30, 30, $email), false);
            $form->addElement(new \XoopsFormText(_AM_JOBS_COMPANY, 'company', 30, 30, $company), false);
            $form->addElement(new \XoopsFormText(_AM_JOBS_TITLE, 'title', 30, 30, $title), false);

            $statusRadio        = new \XoopsFormRadio(_AM_JOBS_STATUS, 'status', $status);
            $statusOptions['1'] = _AM_JOBS_ACTIVE;
            $statusOptions['0'] = _AM_JOBS_INACTIVE;
            $statusRadio->addOptionArray($statusOptions);
            $form->addElement($statusRadio, false);

            $form->addElement(new \XoopsFormText(_AM_JOBS_EXPIRE, 'expire', 30, 30, $expire), false);

            $type_form = new \XoopsFormSelect(_AM_JOBS_TYPE, 'type', $type, '1', false);
            $result5   = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('jobs_type') . ' ORDER BY nom_type');
            while (false !== (list($nom_type) = $xoopsDB->fetchRow($result5))) {
                $type_form->addOption($nom_type, $nom_type);
            }
            $form->addElement($type_form, true);

            $cat_form = new \XoopsFormSelect(_AM_JOBS_CAT, 'cid', $cid);
            $cattree  = $mytree->getChildTreeArray(0, 'title ASC');
            $cat_form->addOption('', _AM_JOBS_SELECTCAT);
            foreach ($cattree as $branch) {
                $branch['prefix'] = substr($branch['prefix'], 0, -1);
                $branch['prefix'] = str_replace('.', '--', $branch['prefix']);
                $cat_form->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
            }
            $form->addElement($cat_form, true);

            $wysiwyg_text_area = jobs_getEditor(_AM_JOBS_DESC2, 'desctext', $desctext, '100%', '200px', 'small');
            $form->addElement($wysiwyg_text_area, true);

            $wysiwyg_requirements_area = jobs_getEditor(_AM_JOBS_REQUIRE, 'requirements', $requirements, '100%', '200px', 'small');
            $form->addElement($wysiwyg_requirements_area, true);

            $salary_tray = new \XoopsFormElementTray(_AM_JOBS_PRICE2);

            $price_form = new \XoopsFormSelect('', 'typeprice', $typeprice, '1', false);
            $result     = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY nom_price');
            while (false !== (list($nom_price) = $xoopsDB->fetchRow($result))) {
                $price_form->addOption($nom_price, $nom_price);
            }
            $salary_tray->addElement(new \XoopsFormText($helper->getConfig('jobs_money'), 'price', 35, 100, $price));
            $salary_tray->addElement($price_form);
            $form->addElement($salary_tray, false);

            $form->addElement(new \XoopsFormText(_AM_JOBS_TEL, 'tel', 30, 30, $tel), false);
            $form->addElement(new \XoopsFormText(_AM_JOBS_TOWN, 'town', 30, 30, $town), false);

            //            $state_form = new \XoopsFormSelect(_AM_JOBS_STATE, "state", $state, "1", false);
            //            $result2 = $xoopsDB->query("select rid, name from " . $xoopsDB->prefix("jobs_region") . " order by rid");
            //            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result2))) {
            //                $state_form->addOption('', _AM_JOBS_SELECT_STATE);
            //                $state_form->addOption($rid, $name);
            //            }

            //            $result5 = $xoopsDB->query("select rid, name from " . $xoopsDB->prefix("jobs_region") . " order by rid");
            //                        while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result5))) {
            //                            $sel = "";
            //                            if ($rid == $state) {
            //                                $sel = "selected";
            //                            }
            //
            //
            //            $form->addElement($state_form, true);

            if ('1' == $helper->getConfig('jobs_show_state')) {
                $result2    = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid');
                $state_form = new \XoopsFormSelect(_AM_JOBS_STATE, 'state', $state, '1', false);
                while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result2))) {
                    $state_form->addOption('', _AM_JOBS_SELECT_STATE);
                    $state_form->addOption($rid, $name);
                }
                $form->addElement($state_form, true);
            } else {
                $form->addElement(new \XoopsFormHidden('state', ''), false);
            }

            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO, 'contactinfo', $contactinfo, 4, 28), false);
            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO1, 'contactinfo1', $contactinfo1, 4, 28), false);
            $form->addElement(new \XoopsFormTextArea(_AM_JOBS_CONTACTINFO2, 'contactinfo2', $contactinfo2, 4, 28), false);

            //            $form->addElement(new \XoopsFormText(_AM_JOBS_RES_HOW_LONG, "expire", 30, 30, $expire), false);
            //
            //            $radio        = new \XoopsFormRadio(_AM_JOBS_STATUS, 'status', $status);
            //            $options["1"] = _AM_JOBS_ACTIVE;
            //            $options["0"] = _AM_JOBS_INACTIVE;
            //            $radio->addOptionArray($options);
            //            $form->addElement($radio, true);
            //
            //            $form->addElement(new \XoopsFormText(_AM_JOBS_EMAIL, "email", 30, 30, $email), false);
            //            $form->addElement(new \XoopsFormText(_AM_JOBS_TEL, "tel", 30, 30, $tel), false);
            //            $form->addElement(new \XoopsFormText(_AM_JOBS_TOWN, "town", 30, 30, $town), false);
            //
            //            $state_form = new \XoopsFormSelect(_AM_JOBS_STATE, "state", $state, "1", false);
            //            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result2))) {
            //                $state_form->addOption('', _AM_JOBS_SELECT_STATE);
            //                $state_form->addOption($rid, $name);
            //            }
            //            $form->addElement($state_form, true);

            //            $form->addElement(new \XoopsFormText(_AM_JOBS_PRICE2, "salary", 40, 50, $salary), false);
            //            $sel_form = new \XoopsFormSelect(_AM_JOBS_SALARYTYPE, "typeprice", $typeprice, "1", false);
            //            while (false !== (list($nom_price) = $xoopsDB->fetchRow($result1))) {
            //                $sel_form->addOption($nom_price, $nom_price);
            //            }
            //            $form->addElement($sel_form);

            //            if ($resume_old) {
            //                if ($resume_old != 'created') {
            //                    $resume_link = "<a href=\"resumes/$resume_old\">$resume_old</a>";
            //
            //                    $form->addElement(new \XoopsFormLabel(_AM_JOBS_ACTUALRES, $resume_link));
            //
            //                    $del_checkbox = new \XoopsFormCheckBox(_AM_JOBS_DELRES, 'del_old', $del_old);
            //                    $del_checkbox->addOption(1, "Yes");
            //                    $form->addElement($del_checkbox);
            //                    $form->addElement(new \XoopsFormFile(_AM_JOBS_UP_NEW_RESUME, 'resume', $helper->getConfig('jobs_maxfilesize')), false);
            //                    $form->addElement(new \XoopsFormHidden('resume_old', $resume_old));
            //
            //                } else {
            //
            //                    $resume_link = "<a href=\"../myresume.php?lid=" . addslashes($lid) . "\">$resume_old</a>";
            //                    $form->addElement(new \XoopsFormLabel(_AM_JOBS_ACTUALRES, $resume_link));
            //                    $del_made_resume = new \XoopsFormCheckBox(_AM_JOBS_DELRES, 'del_made_resume', $del_made_resume);
            //                    $del_made_resume->addOption(1, "Yes");
            //                    $form->addElement($del_made_resume);
            //                    $form->addElement(new \XoopsFormHidden('resume_old', $resume_old));
            //                }
            //            } else {
            //                $form->addElement(new \XoopsFormFile(_AM_JOBS_NEWRES, 'resume', $helper->getConfig('jobs_maxfilesize')), false);
            //            }

            //            $res_radio    = new \XoopsFormRadio(_AM_JOBS_Q_NO_RESUME, 'make_resume', "0");
            //            $options["0"] = _AM_JOBS_DONT_MAKE;
            //            $options["1"] = _AM_JOBS_MAKE_RESUME;
            //            $res_radio->addOptionArray($options);
            //            $form->addElement($res_radio, true);

            $validRadio        = new \XoopsFormRadio(_AM_JOBS_PUBLISHEDCAP, 'valid', $valid);
            $validOptions['1'] = _YES;
            $validOptions['0'] = _NO;
            $validRadio->addOptionArray($validOptions);
            $form->addElement($validRadio, false);

            $premiumRadio        = new \XoopsFormRadio(_AM_JOBS_PREMIUM, 'premium', $premium);
            $premiumOptions['1'] = _YES;
            $premiumOptions['0'] = _NO;
            $premiumRadio->addOptionArray($premiumOptions);
            $form->addElement($premiumRadio, true);

            //            if ($helper->getConfig('jobs_moderate_res_up') == 0) {
            //                $form->addElement(new \XoopsFormHidden("valid", "1"), false);
            //            } else {
            //                $form->addElement(new \XoopsFormHidden("valid", "0"), false);
            //            }

            $form->addElement(new \XoopsFormHidden('photo', $photo), false);
            $form->addElement(new \XoopsFormHidden('view', $view), false);

            $form->addElement(new \XoopsFormHidden('lid', $lid), false);
            $form->addElement(new \XoopsFormHidden('date', $date), false);
            $form->addElement(new \XoopsFormHidden('submit', '1'), false);
            $form->addElement(new \XoopsFormHidden('submitter', $submitter), false);
            $form->addElement(new \XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
            $form->display();
            $submit_form = ob_get_contents();
            ob_end_clean();
            echo $submit_form;
        }
    }
}
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
