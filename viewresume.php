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

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('resume_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}

include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/resume_functions.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
$mytree = new JobTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');

ExpireResume();

$GLOBALS['xoopsOption']['template_main'] = 'jobs_resume.tpl';
include XOOPS_ROOT_PATH . '/header.php';

if (isset($_POST['unlock'])) {
    $unlock = trim($_POST['unlock']);
} elseif (isset($_GET['unlock'])) {
    $unlock = trim($_GET['unlock']);
} else {
    $unlock = '';
}
$xoopsTpl->assign('unlocked', $unlock);

$lid = \Xmf\Request::getInt('lid', 0, 'GET');
$xoopsTpl->assign('id', $lid);

$result      = $xoopsDB->query('SELECT r.lid, r.cid, r.name, r.title, r.exp, r.expire, r.private, r.tel, r.salary, r.typeprice, r.date, r.email, r.submitter, r.usid, r.town, r.state, r.valid, r.resume, r.rphoto, r.view, c.res_lid, c.lid, c.made_resume, c.date, c.usid, p.cod_img, p.lid, p.uid_owner, p.url FROM '
                               . $xoopsDB->prefix('jobs_resume')
                               . ' r LEFT JOIN '
                               . $xoopsDB->prefix('jobs_created_resumes')
                               . ' c ON c.lid = r.lid  LEFT JOIN '
                               . $xoopsDB->prefix('jobs_pictures')
                               . ' p ON r.lid = p.lid WHERE r.lid = '
                               . $xoopsDB->escape($lid)
                               . '');
$recordexist = $xoopsDB->getRowsNum($result);

$updir = $helper->getConfig('jobs_link_upload');
$xoopsTpl->assign('add_from', _JOBS_RES_ADDFROM . ' ' . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _JOBS_RESUME_TITLE);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

$xoopsTpl->assign('ad_exists', $recordexist);
$count = 0;
$x     = 0;
$i     = 0;

$result1 = $xoopsDB->query('SELECT cid FROM ' . $xoopsDB->prefix('jobs_resume') . ' WHERE  lid=' . $xoopsDB->escape($lid) . '');
list($cid) = $xoopsDB->fetchRow($result1);

$result2 = $xoopsDB->query('SELECT cid, pid, title FROM ' . $xoopsDB->prefix('jobs_res_categories') . ' WHERE  cid=' . $xoopsDB->escape($cid) . '');
list($ccid, $pid, $title) = $xoopsDB->fetchRow($result2);

$title      = $myts->htmlSpecialChars($title);
$varid[$x]  = $ccid;
$varnom[$x] = $title;

list($res) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_resume') . " WHERE valid='1' AND cid=" . $xoopsDB->escape($cid) . ''));

if (0 != $pid) {
    $x = 1;
    while (0 != $pid) {
        $result3 = $xoopsDB->query('SELECT cid, pid, title FROM ' . $xoopsDB->prefix('jobs_res_categories') . ' WHERE cid=' . addslashes($pid) . '');
        list($ccid, $pid, $title) = $xoopsDB->fetchRow($result3);

        $title      = $myts->htmlSpecialChars($title);
        $varid[$x]  = $ccid;
        $varnom[$x] = $title;
        ++$x;
    }
    --$x;
}

$subcats   = '';
$arrow     = '&nbsp;<img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/arrow.gif\" alt=\"&raquo;\">";
$backarrow = '&nbsp;<img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/backarrow.gif\" alt=\"&laquo;\">";
while (-1 != $x) {
    $subcats .= " $arrow <a href=\"resumecat.php?cid=" . $varid[$x] . '">' . $varnom[$x] . '</a>';
    --$x;
}
$xoopsTpl->assign('nav_jobs', '<a href="index.php">' . _JOBS_RES_BACKTO . "</a>$backarrow");
$xoopsTpl->assign('nav_main', '<a href="resumes.php">' . _JOBS_MAIN . '</a>');
$xoopsTpl->assign('nav_sub', $subcats);
$xoopsTpl->assign('nav_subcount', $res);

if ($recordexist) {
    list($lid, $cid, $name, $title, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $resume, $rphoto, $view, $res_lid, $rlid, $made_resume, $rdate, $rusid, $cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($result);

    //  Specification for Japan: add  $viewcount_judge for view count up judge
    $viewcount_judge = true;
    $useroffset      = '';
    if ($xoopsUser) {
        $timezone = $xoopsUser->timezone();
        if (isset($timezone)) {
            $useroffset = $xoopsUser->timezone();
        } else {
            $useroffset = $xoopsConfig['default_TZ'];
        }
        //  Specification for Japan: view count up judge
        if ((1 == $xoopsUser->getVar('uid')) || ($xoopsUser->getVar('uid') == $usid)) {
            $viewcount_judge = false;
        }
    }
    //  Specification for Japan: view count up judge
    if (true === $viewcount_judge) {
        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('jobs_resume') . ' SET view=view+1 WHERE lid = ' . $xoopsDB->escape($lid) . '');
    }

    if ('0' == $valid) {
        $xoopsTpl->assign('not_yet_approved', '<font style="color:red;">' . _JOBS_NOT_APPROVED . '</font>');
    }

    $date           = ($useroffset * 3600) + $date;
    $date2          = $date + ($expire * 86400);
    $date           = formatTimestamp($date, 's');
    $date2          = formatTimestamp($date2, 's');
    $rdate          = formatTimestamp($rdate, 's');
    $name           = $myts->htmlSpecialChars($name);
    $title          = $myts->htmlSpecialChars($title);
    $exp            = $myts->htmlSpecialChars($exp);
    $expire         = $myts->htmlSpecialChars($expire);
    $private        = $myts->htmlSpecialChars($private);
    $tel            = $myts->htmlSpecialChars($tel);
    $salary         = $myts->htmlSpecialChars($salary);
    $typeprice      = $myts->htmlSpecialChars($typeprice);
    $submitter      = $myts->htmlSpecialChars($submitter);
    $town           = $myts->htmlSpecialChars($town);
    $state          = $myts->htmlSpecialChars($state);
    $made_resume    = $myts->htmlSpecialChars($made_resume);
    $created_resume = "<a href=\"myresume.php?lid=$lid\">" . _JOBS_VIEWRESUME . '</a>';

    $imprD = '<a href="print.php?op=Rprint&amp;lid=' . addslashes($lid) . '" target="_blank"><img src="assets/images/print.gif" border="0" alt="' . _JOBS_RPRINT . '" width="15" height="11"></a>&nbsp;';

    if ($usid > 0) {
        $xoopsTpl->assign('submitter', _JOBS_SUBMITTED_BY . " <a href='" . XOOPS_URL . '/userinfo.php?uid=' . addslashes($usid) . "'>$submitter</a>");
    } else {
        $xoopsTpl->assign('submitter', _JOBS_SUBMITTED_BY . " $submitter");
    }

    $xoopsTpl->assign('read', "$view " . _JOBS_VIEW2);

    if ($xoopsUser) {
        $calusern = $xoopsUser->getVar('uid', 'E');
        if ($usid == $calusern) {
            $xoopsTpl->assign('modify', '<a href="modresume.php?lid='
                                        . addslashes($lid)
                                        . '"><img src='
                                        . $pathIcon16
                                        . "/edit.png alt='"
                                        . _JOBS_RES_MODIFANN
                                        . "' title='"
                                        . _JOBS_RES_MODIFANN
                                        . "'></a>&nbsp;<a href=\"delresume.php?lid="
                                        . addslashes($lid)
                                        . '"><img src='
                                        . $pathIcon16
                                        . "/delete.png alt='"
                                        . _JOBS_DEL_RESUME
                                        . "' title='"
                                        . _JOBS_DEL_RESUME
                                        . "'></a>");

            $xoopsTpl->assign('add_photos', "<a href=\"../$moduleDirName/view_photos.php?lid=" . addslashes($lid) . '&uid=' . addslashes($usid) . '">' . _JOBS_ADD_PHOTOS . '</a>');
        }
        if ($xoopsUser->isAdmin()) {
            $xoopsTpl->assign('admin', '<a href="admin/modresume.php?lid=' . addslashes($lid) . '"><img src=' . $pathIcon16 . "/edit.png alt='" . _JOBS_MODRESADMIN . "' title='" . _JOBS_MODRESADMIN . "'></a>");
        }
    }

    $state_name = resume_getStateNameFromId($state);

    if (!empty($private) && $unlock != $private) {
        $xoopsTpl->assign('name', _JOBS_NAME_PRIVATE);
    } else {
        $xoopsTpl->assign('name', $name);
    }
    $xoopsTpl->assign('private', $private);
    $xoopsTpl->assign('access', _JOBS_RES_ACCESS);
    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('exp', $exp);
    $xoopsTpl->assign('res_experience_head', _JOBS_RES_EXP);
    $xoopsTpl->assign('local_town', (string)$town);
    $xoopsTpl->assign('state', $state_name);
    $xoopsTpl->assign('local_head', _JOBS_LOCAL);
    $xoopsTpl->assign('job_mustlogin', _JOBS_RES_MUSTLOGIN);
    $xoopsTpl->assign('job_for', _JOBS_FOR);
    $xoopsTpl->assign('xoops_pagetitle', "$title - $exp");

    if ($salary > 0) {
        $xoopsTpl->assign('salary', '<b>' . _JOBS_RES_SALARY . "</b> $salary " . $helper->getConfig('jobs_money') . " - $typeprice");
        $xoopsTpl->assign('price_head', _JOBS_RES_SALARY);
        $xoopsTpl->assign('price_price', '' . $helper->getConfig('jobs_money') . " $salary");
        $xoopsTpl->assign('price_typeprice', (string)$typeprice);
    }

    $xoopsTpl->assign('contact_head', _JOBS_CONTACT);
    $xoopsTpl->assign('contact_email', '<a href="contactresume.php?lid=' . addslashes($lid) . '">' . _JOBS_BYMAIL2 . '</a>');

    if ('' != $resume) {
        if (!empty($private) && $unlock != $private) {
            $xoopsTpl->assign('resume', _JOBS_RES_IS_PRIVATE);
            $xoopsTpl->assign('show_private', _JOBS_RES_PRIVATE_DESC);
        } elseif ('created' !== $resume) {
            $xoopsTpl->assign('resume', "<a href=\"../$moduleDirName/resumes/$resume\">" . _JOBS_VIEWRESUME . '</a>');
        } else {
            $xoopsTpl->assign('resume', $created_resume);
        }
    } else {
        $xoopsTpl->assign('noresume', _JOBS_RES_NORESUME);
    }
    if ('' != $rphoto) {
        $xoopsTpl->assign('photo', '<a href="view_photos.php?lid=' . addslashes($lid) . '&uid=' . addslashes($uid_owner) . "\" target=_self><img src=\"$updir/$url\" alt=\"$title\" width=\"130px\">");
        if ($rphoto > '1') {
            $xoopsTpl->assign('more_photos', _JOBS_MORE_PHOTOS);
        }
        $xoopsTpl->assign('pic_lid', $pic_lid);
        $xoopsTpl->assign('pic_owner', $uid_owner);
    } else {
        $xoopsTpl->assign('rphoto', '');
    }

    $xoopsTpl->assign('date', _JOBS_RES_DATE2 . " $date " . _JOBS_DISPO . " $date2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $imprD");

    $result4 = $xoopsDB->query('SELECT title FROM ' . $xoopsDB->prefix('jobs_res_categories') . ' WHERE cid=' . $xoopsDB->escape($cid) . '');
    list($ctitle) = $xoopsDB->fetchRow($result4);

    $xoopsTpl->assign('link_main', "<a href=\"../$moduleDirName/resumes.php\">" . _JOBS_MAIN . '</a>');
    $xoopsTpl->assign('friend', "<a href=\"../$moduleDirName/sendfriend.php?op=SendResume&amp;lid=" . addslashes($lid) . "\"><img src=\"../$moduleDirName/assets/images/friend.gif\" border=\"0\" alt=\"" . _JOBS_SENDTOFRIEND . '" width="15" height="11"></a>');
    $xoopsTpl->assign('link_cat', '<a href="resumecat.php?cid=' . addslashes($cid) . '">' . _JOBS_GORUB . " $ctitle</a>");
} else {
    $xoopsTpl->assign('no_ad', _JOBS_RES_NOLISTING);
}

include XOOPS_ROOT_PATH . '/footer.php';
