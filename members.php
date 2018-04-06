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
include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";
$mytree                                  = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');
$GLOBALS['xoopsOption']['template_main'] = 'jobs_members.tpl';

include XOOPS_ROOT_PATH . '/header.php';

$usid    = \Xmf\Request::getInt('usid', 0, 'GET');
$comp_id = \Xmf\Request::getInt('comp_id', 0, 'GET');

$module_id = $xoopsModule->getVar('mid');
if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('' . $moduleDirName . '_premium', $perm_itemid, $groups, $module_id)) {
    $permit = '0';
} else {
    $permit = '1';
}
$xoopsTpl->assign('permit', $permit);

if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $isadmin = true;
} else {
    $isadmin = false;
}

$xoopsTpl->assign('title_head', _JOBS_TITLE);
$xoopsTpl->assign('price_head', _JOBS_PRICE);
$xoopsTpl->assign('date_head', _JOBS_ADDED_ON);
$xoopsTpl->assign('local_head', _JOBS_LOCAL2);
$xoopsTpl->assign('views_head', _JOBS_HITS);
$xoopsTpl->assign('replies_head', _JOBS_REPLIES);
$xoopsTpl->assign('expires_head', _JOBS_EXPIRES_ON);
$xoopsTpl->assign('all_company_listings', _JOBS_ALL_COMPANY_LISTINGS);
$xoopsTpl->assign('nav_main', '<a href="index.php">' . _JOBS_MAIN . '</a>');
$xoopsTpl->assign('mydirname', $moduleDirName);
// changes the number of listings per page on the members page  $show = ?
$show = 10;

$min = \Xmf\Request::getInt('min', 0, 'GET');
if (!isset($max)) {
    $max = $min + $show;
}
$orderby = 'l.date ASC';

$this_company = jobs_getACompany($comp_id);
if ($this_company) {
    $logo = $this_company['comp_img'];
    $name = $this_company['comp_name'];
    $logo = "<img src=\"logo_images/$logo\" alt=\"$name\">";
    $xoopsTpl->assign('logo', $logo);
}

$company_name = $this_company['comp_name'];
$xoopsTpl->assign('company', $company_name);

$istheirs     = '';
$edit_company = '';
$modify_link  = '';

if ($xoopsUser) {
    $member_usid = $xoopsUser->getVar('uid', 'E');

    $this_comp = jobs_getCompanyUsers($comp_id, $member_usid);

    if ($this_comp) {
        if ($this_comp['comp_usid'] == $member_usid) {
            $del_company = "<a href='delcompany.php?comp_id=" . addslashes($comp_id) . "'>" . _JOBS_DELETE_COMPANY . '</a>';
            $xoopsTpl->assign('del_company', $del_company);
        } else {
            $del_company = '';
        }

        $istheirs = '1';
        $xoopsTpl->assign('istheirs', $istheirs);
        $edit_company = "<a href='modcompany.php?comp_id=" . addslashes($comp_id) . "'>" . _JOBS_EDIT_COMPANY . '</a>';
        $xoopsTpl->assign('edit_company', $edit_company);
        $add_listing = "<a href='addlisting.php?comp_id=" . addslashes($comp_id) . "'>" . _JOBS_ADDLISTING2 . '</a>';

        $xoopsTpl->assign('add_listing', $add_listing);
    }
}
$xoopsTpl->assign('no_listing', _JOBS_NO_LISTING);
$xoopsTpl->assign('xoops_pagetitle', $company_name);
// For Premium Users
if ('1' == $istheirs) {
    $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_listing') . ' l, ' . $xoopsDB->prefix('jobs_companies') . ' c WHERE  l.company = c.comp_name AND c.comp_id = ' . $xoopsDB->escape($comp_id) . " AND valid='1'");
    list($trow) = $xoopsDB->fetchRow($countresult);

    $sql = 'select l.lid, l.cid, l.title, l.status, l.expire, l.type, l.company, l.price, l.typeprice, l.date, l.email, l.submitter, l.usid, l.town, l.state, l.valid, l.photo, l.view, c.comp_id, c.comp_name, c.comp_img, c.comp_usid, c.comp_user1, c.comp_user2  FROM '
           . $xoopsDB->prefix('jobs_listing')
           . ' l, '
           . $xoopsDB->prefix('jobs_companies')
           . ' c WHERE l.company = c.comp_name and c.comp_id = '
           . $xoopsDB->escape($comp_id)
           . " and valid = '1' ORDER BY $orderby";
    //To show non-approved ads to premium users remove  and valid = '1'

    $result = $xoopsDB->query($sql, $show, $min);
} else {
    // For Non-Premium Users
    $countresult = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_listing') . ' l, ' . $xoopsDB->prefix('jobs_companies') . ' c WHERE  l.company = c.comp_name AND c.comp_id = ' . $xoopsDB->escape($comp_id) . " AND valid='1' AND status!='0'");
    list($trow) = $xoopsDB->fetchRow($countresult);

    $sql    = 'select l.lid, l.cid, l.title, l.status, l.expire, l.type, l.company, l.price, l.typeprice, l.date, l.email, l.submitter, l.usid, l.town, l.state, l.valid, l.photo, l.view, c.comp_id, c.comp_name, c.comp_img, c.comp_usid, c.comp_user1, c.comp_user2  FROM '
              . $xoopsDB->prefix('jobs_listing')
              . ' l, '
              . $xoopsDB->prefix('jobs_companies')
              . ' c WHERE l.company = c.comp_name and c.comp_id = '
              . $xoopsDB->escape($comp_id)
              . "  and valid = '1' and status != '0' ORDER BY $orderby";
    $result = $xoopsDB->query($sql, $show, $min);
}

$trows   = $trow;
$pagenav = '';

if ($trows > '0') {
    $xoopsTpl->assign('min', $min);
    $xoopsTpl->assign('listing_exists', $trows);

    if ($trows > '1') {
        $xoopsTpl->assign('show_nav', true);
        $xoopsTpl->assign('lang_sortby', _JOBS_SORTBY);
        $xoopsTpl->assign('lang_title', _JOBS_TITLE);
        $xoopsTpl->assign('lang_titleatoz', _JOBS_TITLEATOZ);
        $xoopsTpl->assign('lang_titleztoa', _JOBS_TITLEZTOA);
        $xoopsTpl->assign('lang_date', _JOBS_DATE);
        $xoopsTpl->assign('lang_dateold', _JOBS_DATEOLD);
        $xoopsTpl->assign('lang_datenew', _JOBS_DATENEW);
        $xoopsTpl->assign('lang_popularity', _JOBS_POPULARITY);
        $xoopsTpl->assign('lang_popularityleast', _JOBS_POPULARITYLTOM);
        $xoopsTpl->assign('lang_popularitymost', _JOBS_POPULARITYMTOL);
        $xoopsTpl->assign('lang_cursortedby', _JOBS_CURSORTEDBY . 'date');
    }

    while (false !== (list($lid, $cid, $title, $status, $expire, $type, $company, $price, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $photo, $vu, $comp_id, $comp_name, $comp_img, $comp_usid, $comp_user1, $comp_user2) = $xoopsDB->fetchRow($result))) {
        if ($comp_img) {
            $company_logo = "<img src=\"logo_images/$comp_img\" alt=\"$comp_name\">";
            $xoopsTpl->assign('company_logo', $company_logo);
        }
        if (1 == $status) {
            $status_is = _JOBS_ACTIVE;
        }
        if (0 == $status) {
            $status_is = _JOBS_INACTIVE;
        }
        if (2 == $status) {
            $status_is = _JOBS_STORE;
        }

        $rrows     = '';
        $view_now  = '';
        $adminlink = '';

        if ('1' == $istheirs) {
            $replycount = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_replies') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
            list($rrow) = $xoopsDB->fetchRow($replycount);
            $rrows = $rrow;
            $xoopsTpl->assign('reply_count', $rrows);

            $result2 = $xoopsDB->query('SELECT r_lid, lid, date, submitter, message, email, r_usid FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_replies') . ' WHERE lid =' . $xoopsDB->escape($lid) . '');
            list($r_lid, $rlid, $rdate, $rsubmitter, $message, $remail, $r_usid) = $xoopsDB->fetchRow($result2);

            if ($rrows >= 1) {
                $view_now = "<a href='replies.php?lid=" . addslashes($lid) . "'>" . _JOBS_VIEWNOW . '</a>';
            }
        }

        if ($xoopsUser) {
            if ($isadmin) {
                $adminlink = "<a href='admin/main.php?op=ModJob&amp;lid=" . addslashes($lid) . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _JOBS_MODADMIN . "' title='" . _JOBS_MODADMIN . "'></a>";
                $xoopsTpl->assign('isadmin', $isadmin);
            }

            $member_usid = $xoopsUser->getVar('uid', 'E');

            if ('1' == $istheirs) {
                $modify_link = "<a href='modjob.php?lid=" . addslashes($lid) . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _JOBS_MODIFANN . "' title='" . _JOBS_MODIFANN . "'></a>";
            }
        }

        $useroffset = '';
        if ($xoopsUser) {
            $timezone = $xoopsUser->timezone();
            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }
        }
        $date  = ($useroffset * 3600) + $date;
        $new   = jobs_listingnewgraphic($date);
        $date2 = $date + ($expire * 86400);
        $date  = formatTimestamp($date, 's');
        $date2 = formatTimestamp($date2, 's');
        $path  = $mytree->getPathFromId($cid, 'title');
        $path  = substr($path, 1);
        $path  = str_replace('/', ' - ', $path);

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($title) >= 40) {
                $title = substr($title, 0, 39) . '...';
            }
        }

        if ('1' == $helper->getConfig('jobs_show_state')) {
            $state = $myts->htmlSpecialChars($state);
        } else {
            $state = '';
        }

        $xoopsTpl->append('items', [
            'id'           => $lid,
            'cid'          => $cid,
            'title'        => $myts->undoHtmlSpecialChars($title),
            'type'         => $myts->htmlSpecialChars($type),
            'company'      => $myts->undoHtmlSpecialChars($company),
            'price'        => $myts->htmlSpecialChars($price),
            'typeprice'    => $myts->htmlSpecialChars($typeprice),
            'date'         => $myts->htmlSpecialChars($date),
            'email'        => $myts->htmlSpecialChars($email),
            'submitter'    => $myts->htmlSpecialChars($submitter),
            'usid'         => $myts->htmlSpecialChars($usid),
            'town'         => $myts->htmlSpecialChars($town),
            'state'        => $state,
            'valid'        => $myts->htmlSpecialChars($valid),
            'hits'         => $vu,
            'comp_id'      => $myts->htmlSpecialChars($comp_id),
            'comp_name'    => $myts->undoHtmlSpecialChars($comp_name),
            'comp_logo'    => $myts->htmlSpecialChars($comp_img),
            'trows'        => $trows,
            'rrows'        => $rrows,
            'expires'      => $myts->htmlSpecialChars($date2),
            'view_now'     => $view_now,
            'modify_link'  => $modify_link,
            'adminlink'    => $adminlink,
            'edit_company' => $edit_company,
            'new'          => $new,
            'istheirs'     => $istheirs
        ]);
    }

    $comp_id = \Xmf\Request::getInt('comp_id', 0, 'GET');
    //Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if (1 != $linkpages && 0 != $linkpages) {
        $prev = $min - $show;
        if ($prev >= 0) {
            $pagenav .= "<a href='members.php?comp_id=$comp_id&min=$prev&show=$show'><b><u>&laquo;</u></b></a> ";
        }
        $counter     = 1;
        $currentpage = ($max / $show);
        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<b>($counter)</b> ";
            } else {
                $pagenav .= "<a href='members.php?comp_id=$comp_id&min=$mintemp&show=$show'>$counter</a> ";
            }
            ++$counter;
        }
        if ($trows > $max) {
            $pagenav .= "<a href='members.php?comp_id=$comp_id&min=$max&show=$show'>";
            $pagenav .= '<b><u>&raquo;</u></b></a>';
        }
        $xoopsTpl->assign('nav_page', '<b>' . _JOBS_PAGES . "</b>&nbsp;&nbsp; $pagenav");
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
