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
/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();
$moduleDirName = basename(__DIR__);

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups =& $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid = \Xmf\Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('resume_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}

include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/resume_functions.php";
$mytree = new Jobs\ResumeTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');

ExpireResume();

$cid     = \Xmf\Request::getInt('cid', 0, 'GET');
$min     = !isset($_REQUEST['min']) ? null : $_REQUEST['min'];
$show    = !isset($_REQUEST['show']) ? null : $_REQUEST['show'];
$orderby = !isset($_REQUEST['orderby']) ? null : $_REQUEST['orderby'];

$GLOBALS['xoopsOption']['template_main'] = 'jobs_res_category.tpl';
include XOOPS_ROOT_PATH . '/header.php';
$default_sort = $helper->getConfig('jobs_resume_sortorder');

$cid  = ($cid > 0) ? $cid : 0;
$min  = ((int)$min > 0) ? (int)$min : 0;
$show = ((int)$show > 0) ? (int)$show : $helper->getConfig('' . $moduleDirName . '_resume_perpage');
$max  = $min + $show;
if (isset($orderby)) {
    $xoopsTpl->assign('sort_active', $orderby); // added for compact sort
    $orderby = resume_convertorderbyin($orderby);
} else {
    //   To change the red arrows in the active sort to another option
    //   you need to change dateD below to your choice, Default is 'dateD'
    //   options are -  'dateD'   'dateA'  'titleA'   'titleD'   'viewA'   'viewD'   'townA'   'townD'   'stateA'   'stateD'
    $xoopsTpl->assign('show_active', 'dateD');
    $orderby = $default_sort;
}

$orderbyTrans = resume_convertorderbytrans($orderby);
$xoopsTpl->assign('cid', $cid);
$xoopsTpl->assign('add_from', _JOBS_RES_ADDFROM . ' ' . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _JOBS_RESUME_TITLE);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('nav_jobs', '<a href="index.php">' . _JOBS_RES_BACKTO . '</a>');
$xoopsTpl->assign('add_resume', "<a href='addresume.php?cid=" . addslashes($cid) . "'>" . _JOBS_RES_ADDRESUME . '</a>');

$resume_banner = xoops_getbanner();
$xoopsTpl->assign('resume_banner', $resume_banner);
$index_code_place = $helper->getConfig('jobs_index_code_place');
$use_extra_code   = $helper->getConfig('jobs_resume_code');
$jobs_use_banner  = $helper->getConfig('jobs_use_banner');
$index_extra_code = $helper->getConfig('jobs_index_code');
$xoopsTpl->assign('use_extra_code', $use_extra_code);
$xoopsTpl->assign('jobs_use_banner', $jobs_use_banner);
$xoopsTpl->assign('index_extra_code', $index_extra_code);
$xoopsTpl->assign('index_code_place', $index_code_place);
$xoopsTpl->assign('perpage', $helper->getConfig('' . $moduleDirName . '_resume_perpage'));

$categories = resume_MygetItemIds('resume_view');
if (is_array($categories) && count($categories) > 0) {
    if (!in_array($cid, $categories)) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/resume.php", 3, _NOPERM);
    }
} else { // User can't see any category
    redirect_header(XOOPS_URL . '/resume.php', 3, _NOPERM);
}

$backarrow  = '<img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/backarrow.gif\" alt=\"&laquo;\">";
$arrow      = '<img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/arrow.gif\" alt=\"&raquo;\">";
$pathstring = "<a href='index.php'>" . $moduleDirName . '</a> ' . $backarrow . " <a href='resumes.php'>" . _JOBS_RES_LISTINGS . '</a>';
$pathstring .= $mytree->resume_getNicePathFromId($cid, 'title', 'resumecat.php?');
$xoopsTpl->assign('module_name', "$moduleDirName ");
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);

$cat_perms = '';
if (is_array($categories) && count($categories) > 0) {
    $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
}

$countresult = $xoopsDB->query('select COUNT(*) FROM ' . $xoopsDB->prefix('jobs_resume') . " where valid='1' AND cid=" . $xoopsDB->escape($cid) . $cat_perms);
list($trow) = $xoopsDB->fetchRow($countresult);
$trows = $trow;

$result = $xoopsDB->query('select cid, pid, title from ' . $xoopsDB->prefix('jobs_res_categories') . ' where  cid=' . $xoopsDB->escape($cid) . $cat_perms);
list($ccid, $pid, $title) = $xoopsDB->fetchRow($result);

$xoopsTpl->assign('xoops_pagetitle', $title);
$xoopsTpl->assign('all_resumes', _JOBS_RESUMES);
$xoopsTpl->assign('cat_title', $title);
$xoopsTpl->assign('resumes_all', _JOBS_ALL);

//  $categories = resume_MygetItemIds("".$moduleDirName ."_view");

$arr = [];
$arr = $mytree->resume_getFirstChild($cid, 'title');
if (count($arr) > 0) {
    $scount = 1;
    foreach ($arr as $ele) {
        if (in_array($ele['cid'], $categories)) {
            $sub_arr         = [];
            $sub_arr         = $mytree->resume_getFirstChild($ele['cid'], 'title');
            $space           = 0;
            $chcount         = 0;
            $infercategories = '';
            foreach ($sub_arr as $sub_ele) {
                if (in_array($sub_ele['cid'], $categories)) {
                    $chtitle = $myts->htmlSpecialChars($sub_ele['title']);
                    if ($chcount > 5) {
                        $infercategories .= '...';
                        break;
                    }
                    if ($space > 0) {
                        $infercategories .= ', ';
                    }
                    $infercategories .= '<a href="' . XOOPS_URL . "/modules/$moduleDirName/resumecat.php?cid=" . $sub_ele['cid'] . '">' . $chtitle . '</a>';
                    $infercategories .= '&nbsp;(' . resume_getTotalResumes($sub_ele['cid']) . ')';
                    $infercategories .= '&nbsp;' . resume_categorynewgraphic($sub_ele['cid']) . '';
                    ++$space;
                    ++$chcount;
                }
            }

            $xoopsTpl->append('subcategories', [
                'title'           => $myts->htmlSpecialChars($ele['title']),
                'id'              => $ele['cid'],
                'infercategories' => $infercategories,
                'totallisting'    => resume_getTotalResumes($ele['cid'], 1),
                'count'           => $scount,
                'new'             => '&nbsp;' . resume_categorynewgraphic($ele['cid']) . ''
            ]);
            ++$scount;
            $xoopsTpl->assign('lang_subcat', _JOBS_AVAILAB);
        }
    }
} else {
    $xoopsTpl->assign('there_are_listings', true);
}

$pagenav = '';

if ('0' == $trows) {
    $xoopsTpl->assign('show_nav', false);
    $xoopsTpl->assign('no_resumes_to_show', _JOBS_NOANNINCAT);
} elseif ($trows > '0') {
    $xoopsTpl->assign('last_res_head', _JOBS_THE . ' ' . $helper->getConfig('jobs_new_jobs_count') . ' ' . _JOBS_LASTADD);
    $xoopsTpl->assign('last_res_head_exp', _JOBS_RES_EXP);
    $xoopsTpl->assign('last_res_head_title', _JOBS_TITLE);
    $xoopsTpl->assign('last_res_head_salary', _JOBS_PRICE);
    $xoopsTpl->assign('last_res_head_date', _JOBS_DATE);
    $xoopsTpl->assign('last_res_head_local', _JOBS_LOCAL2);
    $xoopsTpl->assign('last_res_head_views', _JOBS_VIEW);
    $xoopsTpl->assign('min', $min);

    $sql     = 'select lid, cid, name, title, exp, expire, private, salary, typeprice, date, town, state, valid, view from ' . $xoopsDB->prefix('jobs_resume') . " where valid='1' AND cid=" . $xoopsDB->escape($cid) . " order by $orderby";
    $result1 = $xoopsDB->query($sql, $show, $min);

    if ($trows > '1') {
        $xoopsTpl->assign('show_nav', true);
        $xoopsTpl->assign('lang_sortby', _JOBS_SORTBY);
        $xoopsTpl->assign('lang_title', _JOBS_TITLE);
        $xoopsTpl->assign('lang_titleatoz', _JOBS_TITLEATOZ);
        $xoopsTpl->assign('lang_titleztoa', _JOBS_TITLEZTOA);
        $xoopsTpl->assign('lang_date', _JOBS_DATE);
        $xoopsTpl->assign('lang_dateold', _JOBS_DATEOLD);
        $xoopsTpl->assign('lang_datenew', _JOBS_DATENEW);
        $xoopsTpl->assign('lang_exp', _JOBS_EXP);
        $xoopsTpl->assign('lang_expltoh', _JOBS_EXPLTOH);
        $xoopsTpl->assign('lang_exphtol', _JOBS_EXPHTOL);
        $xoopsTpl->assign('lang_local', _JOBS_LOCAL2);
        $xoopsTpl->assign('lang_localatoz', _JOBS_LOCALATOZ);
        $xoopsTpl->assign('lang_localztoa', _JOBS_LOCALZTOA);
        $xoopsTpl->assign('lang_popularity', _JOBS_POPULARITY);
        $xoopsTpl->assign('lang_popularityleast', _JOBS_POPULARITYLTOM);
        $xoopsTpl->assign('lang_popularitymost', _JOBS_POPULARITYMTOL);
        $xoopsTpl->assign('lang_cursortedby', sprintf(_JOBS_CURSORTEDBY, resume_convertorderbytrans($orderby)));
    }

    $rank = 1;
    while (false !== (list($lid, $cid, $name, $title, $exp, $expire, $private, $salary, $typeprice, $date, $town, $state, $valid, $vu) = $xoopsDB->fetchRow($result1))) {
        $a_item     = [];
        $name       = $myts->htmlSpecialChars($name);
        $title      = $myts->htmlSpecialChars($title);
        $exp        = $myts->htmlSpecialChars($exp);
        $expire     = $myts->htmlSpecialChars($expire);
        $private    = $myts->htmlSpecialChars($private);
        $salary     = $myts->htmlSpecialChars($salary);
        $town       = $myts->htmlSpecialChars($town);
        $state      = resume_getStateNameFromId($state);
        $useroffset = '';

        if ($xoopsUser) {
            $timezone = $xoopsUser->timezone();
            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }
        }
        $date      = ($useroffset * 3600) + $date;
        $startdate = (time() - (86400 * $helper->getConfig('jobs_countday')));
        if ($startdate < $date) {
            $newitem       = '<img src="' . XOOPS_URL . "/modules/$moduleDirName/assets/images/newred.gif\">";
            $a_item['new'] = $newitem;
        }

        $date = formatTimestamp($date, 's');
        if ($xoopsUser) {
            if ($xoopsUser->isAdmin()) {
                $a_item['admin'] = "<a href='admin/modresume.php?lid=" . addslashes($lid) . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _JOBS_MODRESADMIN . "' title='" . _JOBS_MODRESADMIN . "'></a>";
            }
        }

        $a_item['title']   = "<a href='viewresume.php?lid=" . addslashes($lid) . "'>$title</a>";
        $a_item['name']    = $name;
        $a_item['exp']     = $exp;
        $a_item['private'] = $private;
        if ($salary > 0) {
            $a_item['salary'] = '' . $helper->getConfig('jobs_money') . " $salary";
            // Add $price_typeprice by Tom
            $a_item['price_typeprice'] = (string)$typeprice;
        } else {
            $a_item['salary']          = '';
            $a_item['price_typeprice'] = (string)$typeprice;
        }

        $a_item['date'] = $date;
        $a_item['town'] = '';
        if ($town) {
            $a_item['town'] = $town;
        }
        if ($state) {
            $a_item['state'] = $state;
        }
        $a_item['views'] = $vu;
        ++$rank;
        $xoopsTpl->append('items', $a_item);
    }

    $cid     = ((int)$cid > 0) ? (int)$cid : 0;
    $orderby = resume_convertorderbyout($orderby);

    //Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if (1 != $linkpages && 0 != $linkpages) {
        $prev = $min - $show;
        if ($prev >= 0) {
            $pagenav .= "<a href='resumecat.php?cid=$cid&min=$prev&orderby=$orderby&show=$show'><b><u>&laquo;</u></b></a> ";
        }
        $counter     = 1;
        $currentpage = ($max / $show);
        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<b>($counter)</b> ";
            } else {
                $pagenav .= "<a href='resumecat.php?cid=$cid&min=$mintemp&orderby=$orderby&show=$show'>$counter</a> ";
            }
            ++$counter;
        }
        if ($trows > $max) {
            $pagenav .= "<a href='resumecat.php?cid=$cid&min=$max&orderby=$orderby&show=$show'>";
            $pagenav .= '<b><u>&raquo;</u></b></a>';
        }
    }
    $xoopsTpl->assign('nav_page', $pagenav);
}

include XOOPS_ROOT_PATH . '/footer.php';
