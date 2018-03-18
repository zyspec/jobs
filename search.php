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

$xoopsOption['pagetype'] = 'search';

include __DIR__ . '/../../mainfile.php';
$moduleDirName     = basename(__DIR__);
$search_lang       = '_' . strtoupper($moduleDirName);
$xmid              = $xoopsModule->getVar('mid');
$configHandler     = xoops_getHandler('config');
$xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);

if (1 != $xoopsConfigSearch['enable_search']) {
    header('Location: ' . XOOPS_URL . '/index.php');
    exit();
}

$action = 'search';
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
} elseif (!empty($_POST['action'])) {
    $action = $_POST['action'];
}
$query = '';
if (!empty($_GET['query'])) {
    $query = $_GET['query'];
} elseif (!empty($_POST['query'])) {
    $query = $_POST['query'];
}
$andor = 'AND';
if (!empty($_GET['andor'])) {
    $andor = $_GET['andor'];
} elseif (!empty($_POST['andor'])) {
    $andor = $_POST['andor'];
}
$mid = $uid = $start = 0;
if (!empty($_GET['mid'])) {
    $mid = (int)$_GET['mid'];
} elseif (!empty($_POST['mid'])) {
    $mid = (int)$_POST['mid'];
}
if (!empty($_GET['uid'])) {
    $uid = (int)$_GET['uid'];
} elseif (!empty($_POST['uid'])) {
    $uid = (int)$_POST['uid'];
}
if (!empty($_GET['start'])) {
    $start = (int)$_GET['start'];
} elseif (!empty($_POST['start'])) {
    $start = (int)$_POST['start'];
}

if (!empty($_GET['is_resume'])) {
    $is_resume = (int)$_GET['is_resume'];
} elseif (!empty($_POST['is_resume'])) {
    $is_resume = (int)$_POST['is_resume'];
}

if (!empty($_GET['by_state'])) {
    $by_state = $_GET['by_state'];
} elseif (!empty($_POST['by_state'])) {
    $by_state = $_POST['by_state'];
} else {
    $by_state = '';
}

if (!empty($_GET['by_cat'])) {
    $by_cat = $_GET['by_cat'];
} elseif (!empty($_POST['by_cat'])) {
    $by_cat = $_POST['by_cat'];
} else {
    $by_cat = '';
}

if (!empty($_GET['issearch'])) {
    $issearch = (int)$_GET['issearch'];
} else {
    if (!empty($_POST['issearch'])) {
        $issearch = (int)$_POST['issearch'];
    } else {
        $issearch = '';
    }
}
$state_name = '';
$cat_name   = '';
if (!empty($is_resume)) {
    if (!empty($by_state)) {
        require_once XOOPS_ROOT_PATH . '/modules/jobs/include/resume_functions.php';
        $state_name = resume_getStateNameFromId($by_state);
    }
    if (!empty($by_cat)) {
        require_once XOOPS_ROOT_PATH . '/modules/jobs/include/resume_functions.php';
        $cat_name = resume_getResCatNameFromId($by_cat);
    }
} else {
    if (!empty($by_state)) {
        require_once XOOPS_ROOT_PATH . '/modules/jobs/include/functions.php';
        $state_name = jobs_getStateNameFromId($by_state);
    }
    if (!empty($by_cat)) {
        require_once XOOPS_ROOT_PATH . '/modules/jobs/include/functions.php';
        $cat_name = jobs_getCatNameFromId($by_cat);
    }
}

$queries = [];

if ('results' == $action) {
    if ('' == $query) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
} elseif ('showall' == $action) {
    if ('' == $query || empty($mid)) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
} elseif ('showallbyuser' == $action) {
    if (empty($mid) || empty($uid)) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
} elseif ('showstate' == $action) {
    if (empty($mid) || empty($uid)) {
        redirect_header('search.php', 1, _SR_PLZENTER);
    }
}

$groups            = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gpermHandler      = xoops_getHandler('groupperm');
$available_modules = $gpermHandler->getItemIds('module_read', $groups);

if ('search' == $action) {
    include XOOPS_ROOT_PATH . '/header.php';
    //  $issearch = "1";
    include __DIR__ . '/include/searchform.php';
    $search_form->display();
    include XOOPS_ROOT_PATH . '/footer.php';
    exit();
}

if ('OR' != $andor && 'exact' != $andor && 'AND' != $andor) {
    $andor = 'AND';
}

$myts = \MyTextSanitizer::getInstance();
if ('showallbyuser' != $action) {
    if ('exact' != $andor) {
        $ignored_queries = []; // holds kewords that are shorter than allowed minmum length
        $temp_queries    = preg_split('/[\s,]+/', $query);
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (0 == count($queries)) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
        }
    } else {
        $query = trim($query);
        if (strlen($query) < $xoopsConfigSearch['keyword_min']) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
        }
        $queries = [$myts->addSlashes($query)];
    }
}
switch ($action) {
    case 'results':
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \CriteriaCompo(new \Criteria('hassearch', 1));
        $criteria->add(new \Criteria('isactive', 1));
        $criteria->add(new \Criteria('mid', $xmid));
        $modules = $moduleHandler->getObjects($criteria, true);
        $mids    = isset($_REQUEST['mids']) ? $_REQUEST['mids'] : [];
        if (empty($mids) || !is_array($mids)) {
            unset($mids);
            $mids = array_keys($modules);
        }

        if ((!empty($by_state)) && (!empty($by_cat))) {
            $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . $state_name . ' - ' . $cat_name . ' - ' . implode(' ', $queries);
        } elseif (!empty($by_state)) {
            $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . $state_name . ' - ' . implode(' ', $queries);
        } elseif (!empty($by_cat)) {
            $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . $cat_name . ' - ' . implode(' ', $queries);
        } else {
            $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . implode(' ', $queries);
        }

        include XOOPS_ROOT_PATH . '/header.php';

        echo '<h3>' . _SR_SEARCHRESULTS . "</h3>\n";
        echo _SR_KEYWORDS . ':';
        if ('exact' != $andor) {
            foreach ($queries as $q) {
                echo ' <b>' . htmlspecialchars(stripslashes($q)) . '</b>';
            }
            if (!empty($ignored_queries)) {
                echo '<br>';
                printf(_SR_IGNOREDWORDS, $xoopsConfigSearch['keyword_min']);
                foreach ($ignored_queries as $q) {
                    echo ' <b>' . htmlspecialchars(stripslashes($q)) . '</b>';
                }
            }
        } else {
            echo ' "<b>' . htmlspecialchars(stripslashes($queries[0])) . '</b>"';
        }
        echo '<br>';

        foreach ($mids as $mid) {
            $mid = (int)$mid;
            if (in_array($mid, $available_modules)) {
                $module  = $modules[$mid];
                $results = $module->search($queries, $andor, 5, 0);
                echo '<h3>' . $myts->htmlSpecialChars($module->getVar('name')) . '</h3>';

                if (1 == $is_resume) {
                    echo '<h4>Resumes</h4>';
                }

                $count = count($results);
                if (!is_array($results) || 0 == $count) {
                    if (!empty($by_state)) {
                        echo '' . _JOBS_INSTATE . "<b> $state_name</b><br><br>";
                    }

                    if (!empty($by_cat)) {
                        echo '' . _JOBS_INCATEGORY . "<b> $cat_name</b><br><br>";
                    }

                    echo '<p>' . _SR_NOMATCH . '</p>';
                } else {
                    if (!empty($by_state)) {
                        echo '' . _JOBS_INSTATE . "<b> $state_name</b><br><br>";
                    }

                    if (!empty($by_cat)) {
                        echo '' . _JOBS_INCATEGORY . "<b> $cat_name</b><br><br>";
                    }

                    for ($i = 0; $i < $count; ++$i) {
                        if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                            $results[$i]['link'] = '' . $results[$i]['link'];
                        }

                        if ('1' != $is_resume) {
                            echo '<br><b>' . $myts->undoHtmlSpecialChars($results[$i]['company']) . ' - ' . $myts->undoHtmlSpecialChars($results[$i]['type']) . ' - ';
                        }
                        echo "<a href='" . $results[$i]['link'] . "'>" . $myts->htmlSpecialChars($results[$i]['title']) . '</a></b><br>&nbsp; ' . $myts->undoHtmlSpecialChars($results[$i]['town']) . ', ' . $results[$i]['state'] . "<br>\n";

                        echo '<small>';
                        $results[$i]['uid'] = @(int)$results[$i]['uid'];
                        if (!empty($results[$i]['uid'])) {
                            $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                            echo "&nbsp;&nbsp;<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $results[$i]['uid'] . "'>" . $uname . "</a>\n";
                        }
                        echo !empty($results[$i]['time']) ? ' (' . formatTimestamp((int)$results[$i]['time']) . ')' : '';
                        echo "</small><br>\n";
                    }
                    if ($count >= 5) {
                        $search_url = XOOPS_URL . '/modules/jobs/search.php?query=' . urlencode(stripslashes(implode(' ', $queries)));
                        $search_url .= "&mid=$mid&action=showall&andor=$andor&is_resume=$is_resume&by_state=$by_state&by_cat=$by_cat&issearch=1";
                        echo '<br><a href="' . htmlspecialchars($search_url) . '">' . _SR_SHOWALLR . '</a></p>';
                    }
                }
            }
            unset($results);
            unset($module);
        }
        include __DIR__ . '/include/searchform.php';
        $search_form->display();
        break;
    case 'showall':
    case 'showallbyuser':
        include XOOPS_ROOT_PATH . '/header.php';
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->get($mid);
        $results       = $module->search($queries, $andor, 20, $start, $uid);
        $count         = count($results);
        if (is_array($results) && $count > 0) {
            $next_results = $module->search($queries, $andor, 1, $start + 20, $uid, $is_resume);
            $next_count   = count($next_results);
            $has_next     = false;
            if (is_array($next_results) && 1 == $next_count) {
                $has_next = true;
            }

            echo '<h4>' . _SR_SEARCHRESULTS . "</h4>\n";
            if ('showall' == $action) {
                echo _SR_KEYWORDS . ':';
                if ('exact' != $andor) {
                    foreach ($queries as $q) {
                        echo ' <b>' . htmlspecialchars(stripslashes($q)) . '</b>';
                    }
                } else {
                    echo ' "<b>' . htmlspecialchars(stripslashes($queries[0])) . '</b>"';
                }
                echo '<br>';
            }
            printf(_SR_SHOWING, $start + 1, $start + $count);
            echo '<h5>' . $myts->htmlSpecialChars($module->getVar('name')) . '</h5>';

            if (1 == $is_resume) {
                echo '<h4>Resumes</h4>';
            }

            if (!empty($by_state)) {
                echo '' . _JOBS_INSTATE . "<b> $state_name</b><br><br>";
            }

            if (!empty($by_cat)) {
                echo '' . _JOBS_INCATEGORY . "<b> $cat_name</b><br><br>";
            }

            for ($i = 0; $i < $count; ++$i) {
                if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                    $results[$i]['link'] = 'modules/' . $module->getVar('dirname') . '/' . $results[$i]['link'];
                }
                if ('1' != $is_resume) {
                    echo '<br><b>' . $myts->undoHtmlSpecialChars($results[$i]['company']) . ' - ' . $myts->undoHtmlSpecialChars($results[$i]['type']) . ' - ';
                }
                echo "<a href='" . $results[$i]['link'] . "'>" . $myts->htmlSpecialChars($results[$i]['title']) . '</a></b><br>&nbsp; ' . $myts->undoHtmlSpecialChars($results[$i]['town']) . ', ' . $myts->undoHtmlSpecialChars($results[$i]['state']) . "<br>\n";
                echo '<small>';
                $results[$i]['uid'] = @(int)$results[$i]['uid'];
                if (!empty($results[$i]['uid'])) {
                    $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                    echo "&nbsp;&nbsp;<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $results[$i]['uid'] . "'>" . $uname . "</a>\n";
                }
                echo !empty($results[$i]['time']) ? ' (' . formatTimestamp((int)$results[$i]['time']) . ')' : '';
                echo "</small><br>\n";
            }
            echo '<table><tr>';
            $search_url = XOOPS_URL . '/modules/jobs/search.php?query=' . urlencode(stripslashes(implode(' ', $queries)));
            $search_url .= "&mid=$mid&action=$action&andor=$andor";
            if ('showallbyuser' == $action) {
                $search_url .= "&uid=$uid";
            }
            if ($start > 0) {
                $prev = $start - 20;
                echo '<td align="left">
            ';
                $search_url_prev = $search_url . "&start=$prev";
                echo '<a href="' . htmlspecialchars($search_url_prev) . '">' . _SR_PREVIOUS . '</a></td>
            ';
            }
            echo '<td>&nbsp;&nbsp;</td>';
            if (false != $has_next) {
                $next            = $start + 20;
                $search_url_next = $search_url . "&start=$next";
                echo '<td align="right"><a href="' . htmlspecialchars($search_url_next) . '">' . _SR_NEXT . '</a></td>
            ';
            }
            echo '</tr></table>';
        } else {
            if (!empty($by_state)) {
                echo '' . _JOBS_INSTATE . "<b> $state_name</b><br><br>";
            }

            if (!empty($by_cat)) {
                echo '' . _JOBS_INCATEGORY . "<b> $cat_name</b><br><br>";
            }

            echo '<p>' . _SR_NOMATCH . '</p>';
        }
        include __DIR__ . '/include/searchform.php';
        $search_form->display();
        echo '</p>
    ';
        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
