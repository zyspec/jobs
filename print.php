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
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/resume_functions.php";

/**
 * @param int $lid
 */
function Jprint($lid = 0)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB, $useroffset, $myts, $xoopsLogger, $moduleDirName;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $currenttheme = $xoopsConfig['theme_set'];

    $result = $xoopsDB->query('SELECT lid, title, type, company, desctext, requirements, tel, price, typeprice, contactinfo, date, email, submitter, town, state, photo FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
    list($lid, $title, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $state, $photo) = $xoopsDB->fetchRow($result);

    $title        = $myts->htmlSpecialChars($title);
    $type         = $myts->htmlSpecialChars($type);
    $company      = $myts->htmlSpecialChars($company);
    $desctext     = $myts->displayTarea($desctext, 1, 0, 1, 1, 1);
    $requirements = $myts->displayTarea($requirements, 1, 0, 1, 1, 1);
    $tel          = $myts->htmlSpecialChars($tel);
    $price        = $myts->htmlSpecialChars($price);
    $typeprice    = $myts->htmlSpecialChars($typeprice);
    $contactinfo  = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo));
    $submitter    = $myts->htmlSpecialChars($submitter);
    $town         = $myts->htmlSpecialChars($town);
    $state        = $myts->htmlSpecialChars($state);

    echo '
    <html>
    <head><title>' . $xoopsConfig['sitename'] . '</title>
    <link rel="StyleSheet" href="../../themes/' . $currenttheme . '/style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" text="#000000">
    <table border=0><tr><td>
    <table border=0 width=640 cellpadding=0 cellspacing=1 bgcolor="#000000"><tr><td>
    <table border=0 width=100% cellpadding=8 cellspacing=1 bgcolor="#FFFFFF"><tr><td>';

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
    $date2 = $date + ($helper->getConfig('jobs_days') * 86400);
    $date  = formatTimestamp($date, 's');
    $date2 = formatTimestamp($date2, 's');

    echo '<table width=100% border=0><tr>
    <td>' . _JOBS_LISTING_NUMBER . " ( $lid ) <br>" . _JOBS_SUBMITTED_BY . " $submitter " . _JOBS_FOR . "
    $company<br><br>";

    if ($photo) {
        echo "<tr><td><left><img src=\"logo_images/$photo\" border=0></center>";
    }
    echo '</td>
    </tr><br><br>';
    echo "<tr><td><b>$title :</b> <I>$type</I> ";
    echo '</td>
    </tr><br>
    <tr>
    <td><b>' . _JOBS_DESC . "</b><br><br><div style=\"text-align:justify;\">$desctext</div><P>";
    echo '</td>
    </tr>
    <tr>
    <td><br><br><b>' . _JOBS_REQUIRE . "</b><br><br><div style=\"text-align:justify;\">$requirements</div><P>";

    if (1 == $price) {
        echo '<br><b>' . _JOBS_PRICE2 . '</b> ' . $helper->getConfig('jobs_money') . " $price - $typeprice<br>";
    }
    if ($town) {
        echo '<br><b>' . _JOBS_TOWN . "</b> $town<br>";
    }
    echo '</td>
    </tr><br><br>
    <tr>
    <td><b>' . _JOBS_CONTACTINFO . "</b><br><br><div style=\"text-align:justify;\">$contactinfo</div><p>";
    echo '<br><br>' . _JOBS_DATE2 . " $date " . _JOBS_DISPO . " $date2<br><br>";
    echo '</td>
    </tr>
    </table>';
    echo '<br><br></td></tr></table></td></tr></table>
    <br><br><center>
    ' . _JOBS_EXTRANN . ' <b>' . $xoopsConfig['sitename'] . '</b><br>
    <a href="' . XOOPS_URL . "/modules/$moduleDirName/\">" . XOOPS_URL . "/modules/$moduleDirName/</a>
    </td></tr></table>
    </body>
    </html>";
}

/**
 * @param int $lid
 */
function Rprint($lid = 0)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB,  $useroffset, $myts, $xoopsLogger, $moduleDirName;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $currenttheme = $xoopsConfig['theme_set'];

    $result = $xoopsDB->query('SELECT lid, name, title, exp, private, tel, salary, typeprice, date, email, submitter, town, state FROM ' . $xoopsDB->prefix('jobs_resume') . ' WHERE lid=' . $xoopsDB->escape($lid) . '');
    list($lid, $name, $title, $exp, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $town, $state) = $xoopsDB->fetchRow($result);

    $name      = $myts->htmlSpecialChars($name);
    $title     = $myts->htmlSpecialChars($title);
    $exp       = $myts->htmlSpecialChars($exp);
    $private   = $myts->htmlSpecialChars($private);
    $tel       = $myts->htmlSpecialChars($tel);
    $salary    = $myts->htmlSpecialChars($salary);
    $typeprice = $myts->htmlSpecialChars($typeprice);
    $submitter = $myts->htmlSpecialChars($submitter);
    $town      = $myts->htmlSpecialChars($town);
    $state     = $myts->htmlSpecialChars($state);

    echo '
    <html>
    <head><title>' . $xoopsConfig['sitename'] . '</title>
    <link rel="StyleSheet" href="../../themes/' . $currenttheme . '/style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" text="#000000">
    <table border=0><tr><td>
    <table border=0 width=640 cellpadding=0 cellspacing=1 bgcolor="#000000"><tr><td>
    <table border=0 width=100% cellpadding=8 cellspacing=1 bgcolor="#FFFFFF"><tr><td>';

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
    $date2 = $date + ($helper->getConfig('jobs_res_days') * 86400);
    $date  = formatTimestamp($date, 's');
    $date2 = formatTimestamp($date2, 's');

    echo '<table width=100% border=0><tr>
    <td>' . _JOBS_LISTING_NUMBER . " ( $lid ) <br>" . _JOBS_SUBMITTED_BY . " $submitter <br><br>";
    echo '</td>
    </tr><br><br>';
    echo "<tr><td><b>$name :</b> <i>$title</i> ";
    echo '</td>
    </tr><tr><td><br>';
    if (1 == $salary) {
        echo '<br><b>' . _JOBS_PRICE2 . '</b> ' . $helper->getConfig('jobs_money') . " $salary - $typeprice<br>";
    }
    if ($town) {
        echo '<br><b>' . _JOBS_TOWN . "</b> $town";
        if ($state) {
            $state_name = jobs_getStateNameFromId($state);

            echo ', ' . $state_name . '<br>';
        }
    }
    echo '<br><br>' . _JOBS_DATE2 . " $date " . _JOBS_DISPO . " $date2<br><br>";
    echo '</td>
    </tr>
    </table>';

    echo '<br><br></td></tr></table></td></tr></table>
    <br><br><center>
    ' . _JOBS_EXTRANN . ' <b>' . $xoopsConfig['sitename'] . '</b><br>
    <a href="' . XOOPS_URL . "/modules/$moduleDirName/\">" . XOOPS_URL . "/modules/$moduleDirName/</a>
    </td></tr></table>
    </body>
    </html>";
}

##############################################################

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = \Xmf\Request::getInt('lid', 0, 'GET');
} else {
    $lid = \Xmf\Request::getInt('lid', 0, 'POST');
}

$op = '';
if (!empty($_GET['op'])) {
    $op = $_GET['op'];
} elseif (!empty($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {

    case 'Jprint':
        Jprint($lid);
        break;

    case 'Rprint':
        Rprint($lid);
        break;

    default:
        redirect_header('index.php', 3, '' . _RETURNGLO . '');
        break;
}
