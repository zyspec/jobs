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

include __DIR__ . '/header.php';

if (empty($xoopsUser)) {
    redirect_header(XOOPS_URL . '/user.php', 2, _NOPERM);
}

$moduleDirName = basename(__DIR__);
include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
$mytree                                  = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');
$lid                                     = isset($_GET['lid']) ? (int)$_GET['lid'] : 0;
$GLOBALS['xoopsOption']['template_main'] = 'jobs_replies.tpl';
include XOOPS_ROOT_PATH . '/header.php';

$xoopsTpl->assign('nav_main', '<a href="index.php">' . _JOBS_MAIN . '</a>');
// shows how many replies to show default is 1
$show = 1;

$min = isset($_GET['min']) ? (int)$_GET['min'] : 0;
if (!isset($max)) {
    $max = $min + $show;
}
$orderby = 'date Desc';

$xoopsTpl->assign('lid', $lid);

$countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_replies') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
list($trow) = $xoopsDB->fetchRow($countresult);
$trows = $trow;

$pagenav = '';

if ($trows > '0') {
    $xoopsTpl->assign('last_head', _JOBS_THE . ' ' . $xoopsModuleConfig['jobs_new_jobs_count'] . ' ' . _JOBS_LASTADD);
    $xoopsTpl->assign('last_head_title', _JOBS_TITLE);
    $xoopsTpl->assign('last_head_company', _JOBS_COMPANY);
    $xoopsTpl->assign('last_head_price', _JOBS_PRICE);
    $xoopsTpl->assign('last_head_date', _JOBS_DATE);
    $xoopsTpl->assign('last_head_local', _JOBS_LOCAL2);
    $xoopsTpl->assign('last_head_views', _JOBS_VIEW);
    $xoopsTpl->assign('last_head_photo', _JOBS_PHOTO);
    $xoopsTpl->assign('min', $min);

    $sql    = 'select r_lid, lid, title, date, submitter, message, resume, tele, email, r_usid, company FROM ' . $xoopsDB->prefix('jobs_replies') . ' WHERE lid=' . $xoopsDB->escape($lid) . " order by $orderby";
    $result = $xoopsDB->query($sql, $show, $min);
    if ($trows > '1') {
        $xoopsTpl->assign('show_nav', true);
        $xoopsTpl->assign('lang_sortby', _JOBS_SORTBY);
        $xoopsTpl->assign('lang_title', _JOBS_TITLE);
        $xoopsTpl->assign('lang_titleatoz', _JOBS_TITLEATOZ);
        $xoopsTpl->assign('lang_titleztoa', _JOBS_TITLEZTOA);
        $xoopsTpl->assign('lang_date', _JOBS_DATE);
        $xoopsTpl->assign('lang_dateold', _JOBS_DATEOLD);
        $xoopsTpl->assign('lang_datenew', _JOBS_DATENEW);
        $xoopsTpl->assign('lang_company', _JOBS_COMPANY);
        $xoopsTpl->assign('lang_companyatoz', _JOBS_COMPANYATOZ);
        $xoopsTpl->assign('lang_companyztoa', _JOBS_COMPANYZTOA);
        $xoopsTpl->assign('lang_popularity', _JOBS_POPULARITY);
        $xoopsTpl->assign('lang_popularityleast', _JOBS_POPULARITYLTOM);
        $xoopsTpl->assign('lang_popularitymost', _JOBS_POPULARITYMTOL);
        $xoopsTpl->assign('lang_cursortedby', _JOBS_CURSORTEDBY . '' . $orderby);
    }

    while (list($r_lid, $lid, $title, $date, $submitter, $message, $resume, $tele, $email, $r_usid, $company) = $xoopsDB->fetchRow($result)) {
        $useroffset = '';
        if ($xoopsUser) {
            $timezone = $xoopsUser->timezone();
            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }

            $member_id = $xoopsUser->uid();

            $request1 = $xoopsDB->query('SELECT comp_usid, comp_user1, comp_user2 FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE ' . $member_id . ' IN (comp_usid, comp_user1, comp_user2)');
            list($comp_usid, $comp_user1, $comp_user2) = $xoopsDB->fetchRow($request1);

            $comp_users = [$comp_usid, $comp_user1, $comp_user2];
            if (in_array($member_id, $comp_users)) {
                $xoopsTpl->assign('del_reply', '<a href="delreply.php?r_lid=' . addslashes($r_lid) . '"><img src="assets/images/del.gif" border=0 alt="' . _JOBS_DELETE . '"></a>');
            }
        }
        $r_usid = (int)$r_usid;

        $xoopsTpl->assign('submitter', " <a href='" . XOOPS_URL . "/userinfo.php?uid=$r_usid'>$submitter</a>");

        $date = ($useroffset * 3600) + $date;
        $date = formatTimestamp($date, 's');

        $xoopsTpl->assign('title', $title);
        $xoopsTpl->assign('title_head', _JOBS_REPLY_TITLE);
        $xoopsTpl->assign('date_head', _JOBS_REPLIED_ON);
        $xoopsTpl->assign('submitter_head', _JOBS_SENDBY);
        $xoopsTpl->assign('message_head', _JOBS_REPLY_MESSAGE);
        $xoopsTpl->assign('email_head', _JOBS_EMAIL);
        $xoopsTpl->assign('tele_head', _JOBS_TEL);
        $xoopsTpl->assign('resume_head', _JOBS_RESUME);
        $xoopsTpl->assign('no_resume', _JOBS_NO_RESUME);
        $xoopsTpl->assign('view_resume', _JOBS_VIEW_RESUME);
        $xoopsTpl->assign('email', "<a href ='mailto:$email'>$email</a>");
        $xoopsTpl->append('items', [
            'id'      => $lid,
            'title'   => $myts->undoHtmlSpecialChars($title),
            'date'    => $myts->htmlSpecialChars($date),
            'message' => $myts->displayTarea($message, 1),
            'resume'  => $myts->htmlSpecialChars($resume),
            'tele'    => $myts->htmlSpecialChars($tele)
        ]);
    }

    $lid = (int)$_GET['lid'];
    //Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if ($linkpages != 1 && $linkpages != 0) {
        $prev = $min - $show;
        if ($prev >= 0) {
            $pagenav .= "<a href='replies.php?lid=$lid&min=$prev&show=$show'><b><u>&laquo;</u></b></a> ";
        }
        $counter     = 1;
        $currentpage = ($max / $show);
        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<b>($counter)</b> ";
            } else {
                $pagenav .= "<a href='replies.php?lid=$lid&min=$mintemp&show=$show'>$counter</a> ";
            }
            ++$counter;
        }
        if ($trows > $max) {
            $pagenav .= "<a href='replies.php?lid=$lid&min=$max&show=$show'>";
            $pagenav .= '<b><u>&raquo;</u></b></a>';
        }
        $xoopsTpl->assign('nav_page', '<b>' . _JOBS_REPLY_MESSAGE . "</b>&nbsp;&nbsp; $pagenav");
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
