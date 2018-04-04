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

// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller                                    //
// Author Website : pascal.e-xoops@perso-search.com                          //
// Licence Type   : GPL                                                      //
// ------------------------------------------------------------------------- //

use XoopsModules\Jobs;

//include("admin_header.php");
require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
$admin_lang    = '_AM_' . strtoupper($moduleDirName);
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/jobtree.php";

/** @var Jobs\Helper $helper */
$helper = Jobs\Helper::getInstance();

$myts = \MyTextSanitizer::getInstance();
#  function Index
#####################################################
function Index()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule,  $myts, $moduleDirName, $admin_lang;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $mytree = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation(basename(__FILE__));
    //    $adminObject->addItemButton(_AM_JOBS_ADD_REGION, 'addregion.php', 'add', '');
    //    $adminObject->addItemButton(_AM_JOBS_LISTS, 'lists.php', 'list', '');
    //    $adminObject->displayButton('left', '');

    // Checks your version to see if you are updateing
    $module_version = $xoopsModule->getVar('version');
    if ($module_version < 300) {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_YOUR_USING . " $module_version</legend>" . _AM_JOBS_DATABASE_CHANGE . '<br><br><a href="' . XOOPS_URL . "/modules/$moduleDirName/upgrade/upgrade.php\">  " . _AM_JOBS_UPGRADE_NOW . '</a></fieldset>';
    } else {
        /*
        // logo_images dir setting checker
        $logo_images_dir = XOOPS_ROOT_PATH . "/modules/$moduleDirName/logo_images/";
        if (!is_writable($logo_images_dir) || !is_readable($logo_images_dir)) {
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_CHECKER . "</legend><br>";
            echo "<font color='#FF0000'><b>" . _AM_JOBS_DIRPERMS . "" . $logo_images_dir . "</b></font><br><br>\n";
            echo "</fieldset><br>";
        }

        // resumes dir setting checker
        $resumes_dir = XOOPS_ROOT_PATH . "/modules/$moduleDirName/resumes/";
        if (!is_writable($resumes_dir) || !is_readable($resumes_dir)) {
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_CHECKER . "</legend><br>";
            echo "<font color='#FF0000'><b>" . _AM_JOBS_DIRPERMS . "" . $resumes_dir . "</b></font><br><br>\n";
            echo "</fieldset><br>";
        }

        // photo dir setting checker
        $photo_dir = XOOPS_ROOT_PATH . "/modules/$moduleDirName/photo/";
        if (!is_writable($photo_dir) || !is_readable($photo_dir)) {
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_CHECKER . "</legend><br>";
            echo "<font color='#FF0000'><b>" . _AM_JOBS_DIRPERMS . "" . $photo_dir . "</b></font><br><br>\n";
            echo "</fieldset><br>";
        }

*/

        //        if ($helper->getConfig('jobs_moderated') == '1' || $helper->getConfig('jobs_moderate_up') == '1') {
        //
        //            $result  = $xoopsDB->query(
        //                "select lid, title, date from " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0' order by lid"
        //            );
        //            $numrows = $xoopsDB->getRowsNum($result);
        //            if ($numrows > 0) {
        //                echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_WAIT . "</legend>";
        //                echo "<br>" . _AM_JOBS_THEREIS . " <b>$numrows</b> " . _AM_JOBS_WAIT . "<br><br>";
        //                echo "<table width=100% cellpadding=2 cellspacing=0 border=0>";
        //                $rank = 1;
        //
        //                while (false !== (list($lid, $title, $date) = $xoopsDB->fetchRow($result))) {
        //                    $title = $myts->htmlSpecialChars($title);
        //                    $date2 = formatTimestamp($date, "s");
        //
        //                    if (is_integer($rank / 2)) {
        //                        $color = "even";
        //                    } else {
        //                        $color = "odd";
        //                    }
        //                    echo "<tr class='$color'><td><a href=\"main.php?op=IndexView&amp;lid=$lid\">$title</a></td><td align=right> $date2</td></tr>";
        //                    ++$rank;
        //                }
        //                echo "</table><br>";
        //                echo "</fieldset><br>";
        //            } else {
        //
        //                echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_WAIT . "</legend>";
        //                echo "<br> " . _AM_JOBS_NOANNVAL . "<br><br>";
        //                echo "</fieldset><br>";
        //            }
        //        }

        //        if ($helper->getConfig('jobs_moderate_resume') == '1' || $helper->getConfig('jobs_moderate_res_up') == '1') {
        //
        //            $result1  = $xoopsDB->query(
        //                "select lid, title, date from " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0' order by lid"
        //            );
        //            $numrows1 = $xoopsDB->getRowsNum($result1);
        //            if ($numrows1 > 0) {
        //                echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_RES_WAIT . "</legend>";
        //                echo "<br>" . _AM_JOBS_THEREIS . " <b>$numrows1</b> " . _AM_JOBS_RES_WAIT . "<br><br>";
        //                echo "<table width=100% cellpadding=2 cellspacing=0 border=0>";
        //                $rank = 1;
        //
        //                while (false !== (list($lid, $title, $date) = $xoopsDB->fetchRow($result1))) {
        //                    $title = $myts->htmlSpecialChars($title);
        //                    $date2 = formatTimestamp($date, "s");
        //
        //                    if (is_integer($rank / 2)) {
        //                        $color = "even";
        //                    } else {
        //                        $color = "odd";
        //                    }
        //                    echo "<tr class='$color'><td><a href=\"main.php?op=IndexResumeView&amp;lid=$lid\">$title</a></td><td align=right> $date2</td></tr>";
        //                    ++$rank;
        //                }
        //                echo "</table>";
        //                echo "</fieldset>";
        //            } else {
        //                echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_RES_WAIT . "</legend>";
        //                echo "<br> " . _AM_JOBS_RES_NOAPR . "";
        //                echo "</fieldset><br>";
        //            }
        //        }

        // Add Listing
        //        echo"<br><br><fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_ADD_LINK
        //            . "</legend><a href=\"submitlisting.php\">" . _AM_JOBS_ADD_LINK . "</a>";
        //
        //        echo "</fieldset><br>";

        // Add Resume
        //        echo"<br><br><fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_RES_ADD_LINK
        //            . "</legend><a href=\"addresume.php\">" . _AM_JOBS_RES_ADD_LINK . "</a>";
        //
        //        echo "</fieldset><br>";

        // Modify Listing
        //        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MODANN . "</legend>";
        //        list($numrows) = $xoopsDB->fetchRow(
        //            $xoopsDB->query(
        //                "select COUNT(*) FROM " . $xoopsDB->prefix("jobs_listing") . ""
        //            )
        //        );
        //        if ($numrows > 0) {
        //            echo"<form method=\"post\" action=\"main.php\">" . "" . _AM_JOBS_NUMANN
        //                . " <input type=\"text\" name=\"lid\" size=\"12\" maxlength=\"11\">&nbsp;&nbsp;"
        //                . "<input type=\"hidden\" name=\"op\" value=\"ModJob\">" . "<input type=\"submit\" value=\""
        //                . _AM_JOBS_MODIF . "\">" . "<br> " . _AM_JOBS_ALLMODANN . "" . "</form>";
        //            echo "</fieldset><br>";
        //        }

        // Modify Resume
        //        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_RES_MODRES . "</legend>";
        //        list($numrows) = $xoopsDB->fetchRow(
        //            $xoopsDB->query(
        //                "select COUNT(*) FROM " . $xoopsDB->prefix("jobs_listing") . ""
        //            )
        //        );
        //        if ($numrows > 0) {
        //            echo"<form method=\"post\" action=\"main.php\">" . "" . _AM_JOBS_NUMANN
        //                . " <input type=\"text\" name=\"lid\" size=\"12\" maxlength=\"11\">&nbsp;&nbsp;"
        //                . "<input type=\"hidden\" name=\"op\" value=\"ModResume\">" . "<input type=\"submit\" value=\""
        //                . _AM_JOBS_MODIF . "\">" . "<br> " . _AM_JOBS_RES_ALLMODANN . "" . "</form>";
        //            echo "</fieldset><br>";
        //        }

        // Add Type
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_ADDTYPE . '</legend>';
        echo '<form method="post" action="main.php"><br>
        <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddType">
        <input type="submit" value="' . _AM_JOBS_ADD . '">
        </form>';
        echo '</fieldset><br>';

        // Modify Type
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MODTYPE . '</font></legend>';
        list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_type') . ' '));
        if ($numrows > 0) {
            echo '<form method="post" action="main.php"><br>';
            $result = $xoopsDB->query('SELECT id_type, nom_type FROM ' . $xoopsDB->prefix('jobs_type') . ' ORDER BY nom_type');
            echo '' . _AM_JOBS_TYPE . ' <select name="id_type">';

            while (false !== (list($id_type, $nom_type) = $xoopsDB->fetchRow($result))) {
                $nom_type = $myts->htmlSpecialChars($nom_type);
                echo "<option value=\"$id_type\">$nom_type</option>";
            }
            echo '</select>
            <input type="hidden" name="op" value="ListingModType">
            <input type="submit" value="' . _AM_JOBS_MODIF . '">
            </form>';
            echo '</fieldset><br>';
        }

        // Add price
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_ADDPRICE . '</legend>';
        echo '<form method="post" action="main.php"><br>
        <input type="text" name="type" size="30" maxlength="100">
        <input type="hidden" name="op" value="ListingAddprice">
        <input type="submit" value="' . _AM_JOBS_ADD . '">
        </form>';
        echo '</fieldset><br>';

        // Modify price
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MODPRICE . '</font></legend>';
        list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_price') . ' '));
        if ($numrows > 0) {
            echo '<form method="post" action="main.php">
            <br>';
            $result = $xoopsDB->query('SELECT id_price, nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY nom_price');
            echo '' . _AM_JOBS_TYPE . ' <select name="id_price">';

            while (false !== (list($id_price, $nom_price) = $xoopsDB->fetchRow($result))) {
                $nom_price = $myts->htmlSpecialChars($nom_price);
                echo "<option value=\"$id_price\">$nom_price</option>";
            }
            echo '</select>
            <input type="hidden" name="op" value="ListingModprice">
            <input type="submit" value="' . _AM_JOBS_MODIF . '">
            </form>';
            echo '</fieldset><br>';
        }

        //Manage Companies

        //        $result   = $xoopsDB->query(
        //            "select comp_id, comp_name, comp_date_added from " . $xoopsDB->prefix("jobs_companies")
        //                . " order by comp_name"
        //        );
        //        $comprows = $xoopsDB->getRowsNum($result);
        //        if ($comprows > 0) {
        //            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_COMPANY . "</legend>";
        //            echo "<br>" . _AM_JOBS_THEREIS . " <b>$comprows</b> " . _AM_JOBS_COMPANIES . "<br><br>";
        //            echo "<table width=100% cellpadding=2 cellspacing=0 border=0>";
        //            $rank = 1;
        //
        //            while (false !== (list($comp_id, $comp_name, $comp_date_added) = $xoopsDB->fetchRow($result))) {
        //                $comp_name = $myts->htmlSpecialChars($comp_name);
        //                $date2     = formatTimestamp($comp_date_added, "s");
        //
        //                if (is_integer($rank / 2)) {
        //                    $color = "even";
        //                } else {
        //                    $color = "odd";
        //                }
        //                echo "<tr class='$color'><td><a href=\"modcomp.php?comp_id=$comp_id\">$comp_name</a></td><td align=right> $date2</td></tr>";
        //                ++$rank;
        //            }
        //            echo "</table><br>";
        //            echo "</fieldset><br>";
        //        } else {
        //
        //            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_COMPANY . "</legend>";
        //            echo "<br> " . _AM_JOBS_NOCOMPANY . "<br><br>";
        //            echo "</fieldset><br>";
        //
        //        }
    }
    require_once __DIR__ . '/admin_footer.php';
}

#  function IndexView
#####################################################
/**
 * @param int $lid
 */
function IndexView($lid = 0)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig,  $myts, $moduleDirName, $admin_lang;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $mytree = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");

    $result  = $xoopsDB->query('select lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, usid, town, state, premium, photo from '
                               . $xoopsDB->prefix('jobs_listing')
                               . " WHERE valid='0' AND lid='$lid'");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
        echo '<b>' . _AM_JOBS_WAIT . '</b><br><br>';

        list($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $usid, $town, $state, $premium, $photo) = $xoopsDB->fetchRow($result);

        $date2   = formatTimestamp($date, 's');
        $title   = $myts->undoHtmlSpecialChars($title);
        $status  = $myts->htmlSpecialChars($status);
        $expire  = $myts->htmlSpecialChars($expire);
        $type    = $myts->htmlSpecialChars($type);
        $company = $myts->htmlSpecialChars($company);
        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $desctext = $myts->undoHtmlSpecialChars($myts->displayTarea($desctext, 0, 0, 1, 1, 0));
        } else {
            $desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
        }
        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $requirements = $myts->undoHtmlSpecialChars($myts->displayTarea($requirements, 0, 0, 1, 1, 0));
        } else {
            $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
        }
        $tel          = $myts->htmlSpecialChars($tel);
        $price        = $myts->htmlSpecialChars($price);
        $typeprice    = $myts->htmlSpecialChars($typeprice);
        $contactinfo  = $myts->undoHtmlSpecialChars($contactinfo);
        $contactinfo1 = $myts->undoHtmlSpecialChars($contactinfo1);
        $contactinfo2 = $myts->undoHtmlSpecialChars($contactinfo2);
        $submitter    = $myts->htmlSpecialChars($submitter);
        $usid         = (int)$usid;
        $town         = $myts->htmlSpecialChars($town);
        $state        = stripslashes($state);
        $premium      = $myts->htmlSpecialChars($premium);

        echo '<form action="main.php" method="post">';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo '<table class="outer" border="1"><tr>
            <td class="head">' . _AM_JOBS_NUMANN . " </td><td class=\"head\">$lid / $date2</td>
            </tr><tr>
            <td class=\"head\">" . _AM_JOBS_SENDBY . " </td><td class=\"head\">$submitter</td>
            </tr><tr>
            <td class=\"head\">" . _AM_JOBS_COMPANY2 . " </td><td class=\"head\"><input type=\"text\" name=\"company\" size=\"30\" value=\"$company\"></td>
            </tr><tr>
            <td class=\"head\">" . _AM_JOBS_EMAIL . " </td><td class=\"head\"><input type=\"text\" name=\"email\" size=\"30\" value=\"$email\"></td>
            </tr><tr>
            <td class=\"head\">" . _AM_JOBS_TEL . " </td><td class=\"head\"><input type=\"text\" name=\"tel\" size=\"30\" value=\"$tel\"></td>
            </tr><tr>
            <td class=\"head\">" . _AM_JOBS_TOWN . " </td><td class=\"head\"><input type=\"text\" name=\"town\" size=\"30\" value=\"$town\"></td>
            </tr>";

        if ('1' == $helper->getConfig('jobs_show_state')) {
            echo '<tr>
    <td class="head">' . _AM_JOBS_STATE1 . ' </td><td class="head"><select name="state">';
            $result5 = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid');
            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result5))) {
                $sel = '';
                if ($rid == $state) {
                    $sel = 'selected';
                }
                echo "<option value=\"$rid\" $sel>$name</option>";
            }
            echo '</select></td>
    </tr>';
        } else {
            echo '<input type="hidden" name="state" value="">';
        }

        echo "<tr>
        <td class='head'>" . constant($admin_lang . '_PREMIUM') . "</td><td class='head'>
        <input type=\"radio\" name=\"premium\" value=\"1\"";
        if ('1' == $premium) {
            echo 'checked';
        }
        echo '>' . constant($admin_lang . '_YES') . '&nbsp;&nbsp;

        <input type="radio" name="premium" value="0"';
        if ('0' == $premium) {
            echo 'checked';
        }
        echo '>' . constant($admin_lang . '_NO') . '&nbsp;&nbsp; </td></tr>';

        echo '<tr>
            <td class="head">' . _AM_JOBS_CAT . ' </td><td class="head">';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo '</td>
            </tr>';

        echo '<tr>
        <td class="head">' . _AM_JOBS_TITLE2 . " </td><td class=\"head\"><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
        </tr><tr>
        <td class=\"head\">" . _AM_JOBS_EXPIRE . " </td><td class=\"head\"><input type=\"text\" name=\"expire\" size=\"30\" value=\"$expire\"></td>
        </tr><tr>
        <td class=\"head\">" . _AM_JOBS_TYPE . ' </td><td class="head"><select name="type">';

        $result5 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('jobs_type') . ' ORDER BY nom_type');
        while (false !== (list($nom_type) = $xoopsDB->fetchRow($result5))) {
            $sel = '';
            if ($nom_type == $type) {
                $sel = 'selected';
            }
            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }
        echo '</select></td>
            </tr>';
        echo '<tr><td  class="head">' . _AM_JOBS_STATUS . '</td><td class="head"><input type="radio" name="status" value="0"';
        if ('1' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_JOBS_ACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="1"';
        if ('0' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_JOBS_INACTIVE . '</td></tr>';
        echo '<td class="head">' . _AM_JOBS_DESC2 . ' </td><td class="head">';
        $wysiwyg_text_area = jobs_getEditor(_AM_JOBS_DESC2, 'desctext', $desctext, '100%', '200px', 'small');
        echo $wysiwyg_text_area->render();
        echo '</td></tr>';

        echo '<tr><td class="head">' . _AM_JOBS_REQUIRE . ' </td><td class="head">';
        $wysiwyg_requirements_area = jobs_getEditor(_AM_JOBS_REQUIRE, 'requirements', $requirements, '100%', '200px', 'small');
        echo $wysiwyg_requirements_area->render();
        echo '</td></tr>';

        echo '<td class="head">' . _AM_JOBS_PRICE2 . " </td><td class=\"head\"><input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> " . $helper->getConfig('jobs_money') . '';

        $result3 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY id_price');
        echo " <select name=\"typeprice\"><option value=\"$typeprice\">$typeprice</option>";
        while (false !== (list($nom_price) = $xoopsDB->fetchRow($result3))) {
            echo "<option value=\"$nom_price\">$nom_price</option>";
        }
        echo '</select></td>';
        echo '<tr><td class="head"><b>' . _AM_JOBS_CONTACTINFO . "</b></td><td class=\"head\"><textarea name=\"contactinfo\" cols=\"25\" rows=\"6\">$contactinfo</textarea></td>";
        echo '</tr><tr>';

        echo '<tr><td class="head"><b>' . _AM_JOBS_CONTACTINFO1 . "</b></td><td class=\"head\"><textarea name=\"contactinfo1\" cols=\"25\" rows=\"6\">$contactinfo1</textarea></td>";
        echo '</tr><tr>';

        echo '<tr><td class="head"><b>' . _AM_JOBS_CONTACTINFO2 . "</b></td><td class=\"head\"><textarea name=\"contactinfo2\" cols=\"25\" rows=\"6\">$contactinfo2</textarea></td>";
        echo '</tr><tr><tr>
            <td class="head">&nbsp;</td><td class="head"><br><br><select name="op">
            <option value="ListingValid"> ' . _AM_JOBS_OK . '
            <option value="ListingDel"> ' . _AM_JOBS_DEL . '
            </select><input type="submit" value="' . _AM_JOBS_GO . '"></td>
            </tr></table>';
        echo '<input type="hidden" name="valid" value="Yes">';
        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";
        echo '<input type="hidden" name="photo" value="">';
        echo "<input type=\"hidden\" name=\"usid\" value=\"$usid\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
            </form>";
        echo '</td></tr></table>';
        echo '<br>';
    }
    require_once __DIR__ . '/admin_footer.php';
}

#  function IndexView
#####################################################
/**
 * @param int $lid
 */
function IndexResumeView($lid = 0)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig,  $myts, $moduleDirName, $admin_lang;

    $mytree = new JobTree($xoopsDB->prefix('jobs_res_categories'), 'cid', 'pid');

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");

    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $result  = $xoopsDB->query('select lid, cid, title, status, exp, expire, private, tel, salary, typeprice, date, email, submitter, usid, town, state, valid, resume from ' . $xoopsDB->prefix('jobs_resume') . " WHERE valid='0' AND lid='$lid'");
    $numrows = $xoopsDB->getRowsNum($result);
    if ($numrows > 0) {
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
        echo '<b>' . _AM_JOBS_RES_WAIT . '</b><br><br>';

        list($lid, $cid, $title, $status, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $resume) = $xoopsDB->fetchRow($result);

        $date2     = formatTimestamp($date, 's');
        $title     = $myts->htmlSpecialChars($title);
        $status    = $myts->htmlSpecialChars($status);
        $exp       = $myts->htmlSpecialChars($exp);
        $expire    = $myts->htmlSpecialChars($expire);
        $private   = $myts->htmlSpecialChars($private);
        $tel       = $myts->htmlSpecialChars($tel);
        $salary    = $myts->htmlSpecialChars($salary);
        $typeprice = $myts->htmlSpecialChars($typeprice);
        $submitter = $myts->htmlSpecialChars($submitter);
        $town      = $myts->htmlSpecialChars($town);
        $state     = $myts->htmlSpecialChars($state);

        echo '<form action="main.php" method="post">';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo '<table><tr>
            <td>' . _AM_JOBS_NUMANN . " </td><td>$lid / $date2</td>
            </tr><tr>
            <td>" . _AM_JOBS_SENDBY . " </td><td>$submitter</td>
            </tr><tr>
            <td>" . _AM_JOBS_EMAIL . " </td><td><input type=\"text\" name=\"email\" size=\"30\" value=\"$email\"></td>
            </tr><tr>
            <td>" . _AM_JOBS_TEL . " </td><td><input type=\"text\" name=\"tel\" size=\"30\" value=\"$tel\"></td>
            </tr><tr>
            <td>" . _AM_JOBS_TOWN . " </td><td><input type=\"text\" name=\"town\" size=\"30\" value=\"$town\"></td>
            </tr>";

        if ('1' == $helper->getConfig('jobs_show_state')) {
            echo '<tr>
    <td>' . _AM_JOBS_STATE1 . ' </td><td><select name="state">';
            $result5 = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid');
            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result5))) {
                $sel = '';
                if ($rid == $state) {
                    $sel = 'selected';
                }
                echo "<option value=\"$rid\" $sel>$name</option>";
            }
            echo '</select></td>
    </tr>';
        } else {
            echo '<input type="hidden" name="state" value="">';
        }

        echo '<tr>
            <td>' . _AM_JOBS_TITLE2 . " </td><td><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
            </tr>";
        echo '<tr>
            <td>' . _AM_JOBS_RES_EXP . " </td><td><input type=\"text\" name=\"exp\" size=\"30\" value=\"$exp\"></td>
            </tr><tr>
            <td>" . _AM_JOBS_EXPIRE . " </td><td><input type=\"text\" name=\"expire\" size=\"30\" value=\"$expire\"></td>
            </tr><tr>
            <td>" . _AM_JOBS_RES_PRIVATE . " </td><td><input type=\"text\" name=\"private\" size=\"30\" value=\"$private\"></td>
            </tr><tr>
            <td>" . _AM_JOBS_RESUME . " </td><td><input type=\"text\" name=\"resume\" size=\"30\" value=\"$resume\"></td>
            </tr><tr>";
        echo '<td>' . _AM_JOBS_PRICE2 . " </td><td><input type=\"text\" name=\"salary\" size=\"20\" value=\"$salary\"> " . $helper->getConfig('jobs_money') . '';

        $result3 = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY id_price');
        echo " <select name=\"typeprice\"><option value=\"$typeprice\">$typeprice</option>";
        while (false !== (list($nom_price) = $xoopsDB->fetchRow($result3))) {
            echo "<option value=\"$nom_price\">$nom_price</option>";
        }
        echo '</select></td></tr>';
        echo '<tr><td>' . _AM_JOBS_STATUS . '</td><td><input type="radio" name="status" value="0"';
        if ('1' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_JOBS_ACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="1"';
        if ('0' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_JOBS_INACTIVE . '</td></tr>';
        echo '<tr>
            <td>' . _AM_JOBS_CAT . ' </td><td>';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo '</td>
            </tr><tr>
            <td>&nbsp;</td><td><select name="op">
            <option value="ResumeValid"> ' . _AM_JOBS_OK . '
            <option value="ResumeDel"> ' . _AM_JOBS_DEL . '
            </select><input type="submit" value="' . _AM_JOBS_GO . '"></td>
            </tr></table>';
        echo '<input type="hidden" name="valid" value="Yes">';
        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"$date\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
            </form>";
        echo '</td></tr></table>';
        echo '<br>';
    }
    require_once __DIR__ . '/admin_footer.php';
}

#  function ModJob
#####################################################
/**
 * @param int $lid
 */
function ModJob($lid = 0)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig,  $myts, $desctext, $requirements, $moduleDirName, $admin_lang;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $mytree = new JobTree($xoopsDB->prefix('jobs_categories'), 'cid', 'pid');

    require_once __DIR__ . '/admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('jobs.php');
    $adminObject->addItemButton(_AM_JOBS_CATEGORYLIST, 'jobs.php', 'list');
    $adminObject->displayButton('left', '');

    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MODANN . '</legend>';

    $result = $xoopsDB->query('select lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, usid, town, state, valid, premium, photo from '
                              . $xoopsDB->prefix('jobs_listing')
                              . " where lid=$lid");

    while (false !== (list($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $usid, $town, $state, $valid, $premium, $photo) = $xoopsDB->fetchRow($result))) {
        $title   = $myts->htmlSpecialChars($title);
        $status  = $myts->htmlSpecialChars($status);
        $expire  = $myts->htmlSpecialChars($expire);
        $type    = $myts->htmlSpecialChars($type);
        $company = $myts->htmlSpecialChars($company);
        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $desctext = $myts->undoHtmlSpecialChars($myts->displayTarea($desctext, 0, 0, 1, 1, 0));
        } else {
            $desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
        }
        if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
            || 'dhtml' === $helper->getConfig('jobs_form_options')) {
            $requirements = $myts->undoHtmlSpecialChars($myts->displayTarea($requirements, 0, 0, 1, 1, 0));
        } else {
            $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
        }
        $tel          = $myts->htmlSpecialChars($tel);
        $price        = $myts->htmlSpecialChars($price);
        $typeprice    = $myts->htmlSpecialChars($typeprice);
        $contactinfo  = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo, 0, 0, 0, 0, 0));
        $contactinfo1 = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo1, 0, 0, 0, 0, 0));
        $contactinfo2 = $myts->undoHtmlSpecialChars($myts->displayTarea($contactinfo2, 0, 0, 0, 0, 0));
        $submitter    = $myts->htmlSpecialChars($submitter);
        $usid         = (int)$usid;
        $town         = $myts->htmlSpecialChars($town);
        $state        = stripslashes($state);
        $premium      = $myts->htmlSpecialChars($premium);
        $date2        = formatTimestamp($date, 's');

        echo '<form action="main.php" method=post>';
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo '<table class="outer" border=0><tr>
        <td class="outer">' . _AM_JOBS_NUMANN . " </td><td class=\"odd\">$lid &nbsp;" . _AM_JOBS_SUBMITTED_ON . "&nbsp; $date2</td>
        </tr><tr>
        <td class=\"outer\">" . _AM_JOBS_SENDBY . " </td><td class=\"odd\">$submitter</td>
        </tr><tr>
        <td class=\"outer\">" . _AM_JOBS_EMAIL . " </td><td class=\"odd\"><input type=\"text\" name=\"email\" size=\"30\" value=\"$email\"></td>
        </tr><tr>
        <td class=\"outer\">" . _AM_JOBS_COMPANY2 . " </td><td class=\"odd\"><input type=\"text\" name=\"company\" size=\"30\" value=\"$company\"></td>
        </tr><tr>
        <td class=\"outer\">" . _AM_JOBS_TITLE2 . " </td><td class=\"odd\"><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
        </tr>";
        echo "<tr><td class='outer'>" . _AM_JOBS_STATUS1 . "</td><td class='odd'><input type=\"radio\" name=\"status\" value=\"1\"";
        if ('1' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_JOBS_ACTIVE . '&nbsp;&nbsp; <input type="radio" name="status" value="0"';
        if ('0' == $status) {
            echo 'checked';
        }
        echo '>' . _AM_JOBS_INACTIVE . '</td></tr>';
        echo '<tr>
    <td class="outer">' . _AM_JOBS_EXPIRE . " </td><td class=\"odd\"><input type=\"text\" name=\"expire\" size=\"30\" value=\"$expire\"></td>
    </tr><tr>
    <td class=\"outer\">" . _AM_JOBS_TYPE . ' </td><td class="odd"><select name="type">';

        $result5 = $xoopsDB->query('SELECT nom_type FROM ' . $xoopsDB->prefix('jobs_type') . ' ORDER BY nom_type');
        while (false !== (list($nom_type) = $xoopsDB->fetchRow($result5))) {
            $sel = '';
            if ($nom_type == $type) {
                $sel = 'selected';
            }
            echo "<option value=\"$nom_type\" $sel>$nom_type</option>";
        }
        echo '</select></td>
    </tr><tr>
    <td class="outer">' . _AM_JOBS_CAT2 . ' </td><td class="odd">';
        $mytree->makeMySelBox('title', 'title', $cid);
        echo '</td>
    </tr><tr>
    <td class="outer">' . _AM_JOBS_DESC2 . ' </td><td class="odd">';
        $wysiwyg_text_area = jobs_getEditor(_AM_JOBS_DESC2, 'desctext', $desctext, '100%', '200px', 'small');
        echo $wysiwyg_text_area->render();
        echo '</td></tr>';
        echo '<td class="outer">' . _AM_JOBS_REQUIRE . ' </td><td class="odd">';
        $wysiwyg_requirements_area = jobs_getEditor(_AM_JOBS_REQUIRE, 'requirements', $requirements, '100%', '200px', 'small');
        echo $wysiwyg_requirements_area->render();
        echo '</td></tr><tr>';
        echo '<td class="outer">' . _AM_JOBS_PRICE2 . ' </td><td class="odd">' . $helper->getConfig('jobs_money') . "&nbsp;&nbsp;<input type=\"text\" name=\"price\" size=\"20\" value=\"$price\"> ";

        $result = $xoopsDB->query('SELECT nom_price FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY nom_price');
        echo " <select name=\"id_price\"><option value=\"$typeprice\">$typeprice</option>";
        while (false !== (list($nom_price) = $xoopsDB->fetchRow($result))) {
            $nom_price = $myts->htmlSpecialChars($nom_price);
            echo "<option value=\"$nom_price\">$nom_price</option>";
        }
        echo '</select></td>';
        echo '</tr><tr>
    <td class="outer">' . _AM_JOBS_TEL . " </td><td class=\"odd\"><input type=\"text\" name=\"tel\" size=\"30\" value=\"$tel\"></td>
    </tr><tr>
    <td class=\"outer\">" . _AM_JOBS_TOWN . " </td><td class=\"odd\"><input type=\"text\" name=\"town\" size=\"30\" value=\"$town\"></td>
    </tr>";

        if ('1' == $helper->getConfig('jobs_show_state')) {
            echo '<tr>
    <td class="outer">' . _AM_JOBS_STATE1 . ' </td><td class="odd"><select name="state">';
            $result5 = $xoopsDB->query('SELECT rid, name FROM ' . $xoopsDB->prefix('jobs_region') . ' ORDER BY rid');
            while (false !== (list($rid, $name) = $xoopsDB->fetchRow($result5))) {
                $sel = '';
                if ($rid == $state) {
                    $sel = 'selected';
                }
                echo "<option value=\"$rid\" $sel>$name</option>";
            }
            echo '</select></td>
    </tr>';
        } else {
            echo '<input type="hidden" name="state" value="">';
        }

        echo "<tr>
    <td class='outer'>" . constant($admin_lang . '_PREMIUM') . "</td><td class='odd'>
    <input type=\"radio\" name=\"premium\" value=\"1\"";
        if ('1' == $premium) {
            echo 'checked';
        }
        echo '>' . constant($admin_lang . '_YES') . '&nbsp;&nbsp;

    <input type="radio" name="premium" value="0"';
        if ('0' == $premium) {
            echo 'checked';
        }
        echo '>' . constant($admin_lang . '_NO') . '&nbsp;&nbsp; </td></tr>';
        echo '<tr><td class="outer">' . _AM_JOBS_CONTACTINFO . "</td><td class=\"odd\"><textarea name=\"contactinfo\" cols=\"28\" rows=\"4\">$contactinfo</textarea></td></tr>";

        echo '<tr><td class="outer">' . _AM_JOBS_CONTACTINFO1 . "</td><td class=\"odd\"><textarea name=\"contactinfo1\" cols=\"28\" rows=\"4\">$contactinfo1</textarea></td></tr>";

        echo '<tr><td class="outer">' . _AM_JOBS_CONTACTINFO2 . "</td><td class=\"odd\"><textarea name=\"contactinfo2\" cols=\"28\" rows=\"4\">$contactinfo2</textarea></td>";

        $time = time();

        echo '</tr><tr>
    <td>&nbsp;</td><td><select name="op">
    <option value="ModJobS"> ' . _AM_JOBS_MODIF . '
    <option value="ListingDel"> ' . _AM_JOBS_DEL . '
    </select><input type="submit" value="' . _AM_JOBS_GO . '"></td>
    </tr></table>';
        echo '<input type="hidden" name="valid" value="1">';
        echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
        echo "<input type=\"hidden\" name=\"usid\" value=\"$usid\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"$time\">";
        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">
    </form><br>";
    }
    echo '</fieldset><br>';
    require_once __DIR__ . '/admin_footer.php';
}

#  function ModJobS
#####################################################
/**
 * @param $lid
 * @param $cid
 * @param $title
 * @param $status
 * @param $expire
 * @param $type
 * @param $company
 * @param $desctext
 * @param $requirements
 * @param $tel
 * @param $price
 * @param $typeprice
 * @param $contactinfo
 * @param $contactinfo1
 * @param $contactinfo2
 * @param $date
 * @param $email
 * @param $submitter
 * @param $usid
 * @param $town
 * @param $state
 * @param $valid
 * @param $premium
 */
function ModJobS(
    $lid,
    $cid,
    $title,
    $status,
    $expire,
    $type,
    $company,
    $desctext,
    $requirements,
    $tel,
    $price,
    $typeprice,
    $contactinfo,
    $contactinfo1,
    $contactinfo2,
    $date,
    $email,
    $submitter,
    $usid,
    $town,
    $state,
    $valid,
    $premium
) {
    global $xoopsDB, $xoopsConfig,  $myts, $moduleDirName, $admin_lang;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $title   = $myts->addSlashes($title);
    $status  = $myts->addSlashes($status);
    $expire  = $myts->addSlashes($expire);
    $type    = $myts->addSlashes($type);
    $company = $myts->addSlashes($company);
    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $desctext = $myts->displayTarea($desctext, 0, 0, 1, 1, 0);
    } else {
        $desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
    }
    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $requirements = $myts->displayTarea($requirements, 0, 0, 1, 1, 0);
    } else {
        $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
    }
    $tel          = $myts->addSlashes($tel);
    $price        = $myts->addSlashes($price);
    $typeprice    = $myts->addSlashes($typeprice);
    $contactinfo  = $myts->displayTarea($contactinfo, 0, 0, 0, 0, 0);
    $contactinfo1 = $myts->displayTarea($contactinfo1, 0, 0, 0, 0, 0);
    $contactinfo2 = $myts->displayTarea($contactinfo2, 0, 0, 0, 0, 0);
    $submitter    = $myts->addSlashes($submitter);
    $usid         = (int)$usid;
    $town         = $myts->addSlashes($town);
    $state        = $myts->addSlashes($state);
    $premium      = $myts->addSlashes($premium);

    $xoopsDB->query('update '
                    . $xoopsDB->prefix('jobs_listing')
                    . " set cid='$cid', title='$title', status='$status', expire='$expire', type='$type', company='$company', desctext='$desctext', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice', contactinfo='$contactinfo', contactinfo1='$contactinfo1', contactinfo2='$contactinfo2', date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', premium='$premium' where lid=$lid");

    redirect_header('jobs.php', 3, _AM_JOBS_JOBMOD);
}

#  function ListingDel
#####################################################
/**
 * @param $lid
 */
function ListingDel($lid)
{
    global $xoopsDB, $moduleDirName, $admin_lang;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('jobs_listing') . " where lid=$lid");

    redirect_header('index.php', 3, _AM_JOBS_JOBDEL);
}

#  function ListingDel
#####################################################
/**
 * @param $lid
 * @param $resume
 */
function ResumeDel($lid, $resume)
{
    global $xoopsDB, $moduleDirName, $admin_lang;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('jobs_resume') . " where lid=$lid");

    $destination = XOOPS_ROOT_PATH . "/modules/$moduleDirName/resumes";
    if ($resume) {
        if (file_exists("$destination/$resume")) {
            unlink("$destination/$resume");
        }
    }

    redirect_header('index.php', 3, _AM_JOBS_RES_DEL);
}

#  function ListingValid
#####################################################
/**
 * @param $lid
 * @param $cid
 * @param $title
 * @param $status
 * @param $expire
 * @param $type
 * @param $company
 * @param $desctext
 * @param $requirements
 * @param $tel
 * @param $price
 * @param $typeprice
 * @param $contactinfo
 * @param $contactinfo1
 * @param $contactinfo2
 * @param $date
 * @param $email
 * @param $submitter
 * @param $usid
 * @param $town
 * @param $state
 * @param $valid
 * @param $premium
 * @param $photo
 */
function ListingValid(
    $lid,
    $cid,
    $title,
    $status,
    $expire,
    $type,
    $company,
    $desctext,
    $requirements,
    $tel,
    $price,
    $typeprice,
    $contactinfo,
    $contactinfo1,
    $contactinfo2,
    $date,
    $email,
    $submitter,
    $usid,
    $town,
    $state,
    $valid,
    $premium,
    $photo
) {
    global $xoopsDB, $xoopsConfig,  $myts, $meta, $moduleDirName, $admin_lang;
    /** @var Jobs\Helper $helper */
    $helper = Jobs\Helper::getInstance();

    $title   = $myts->addSlashes($title);
    $status  = $myts->addSlashes($status);
    $expire  = $myts->addSlashes($expire);
    $type    = $myts->addSlashes($type);
    $company = $myts->addSlashes($company);
    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $desctext = $myts->displayTarea($desctext, 0, 0, 1, 1, 0);
    } else {
        $desctext = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
    }
    if ('dhtmltextarea' === $helper->getConfig('jobs_form_options')
        || 'dhtml' === $helper->getConfig('jobs_form_options')) {
        $requirements = $myts->displayTarea($requirements, 0, 0, 1, 1, 0);
    } else {
        $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
    }
    $tel          = $myts->addSlashes($tel);
    $price        = $myts->addSlashes($price);
    $typeprice    = $myts->addSlashes($typeprice);
    $contactinfo  = $myts->displayTarea($contactinfo, 0, 0, 0, 0, 0);
    $contactinfo1 = $myts->displayTarea($contactinfo1, 0, 0, 0, 0, 0);
    $contactinfo2 = $myts->displayTarea($contactinfo2, 0, 0, 0, 0, 0);
    $submitter    = $myts->addSlashes($submitter);
    $usid         = (int)$usid;
    $town         = $myts->addSlashes($town);
    $state        = $myts->addSlashes($state);
    $premium      = $myts->addSlashes($premium);

    $xoopsDB->query('update '
                    . $xoopsDB->prefix('jobs_listing')
                    . " set cid='$cid', title='$title', status='$status', expire='$expire', type='$type', company='$company', desctext='$desctext', requirements='$requirements', tel='$tel', price='$price', typeprice='$typeprice', contactinfo='$contactinfo', contactinfo1='$contactinfo1', contactinfo2='$contactinfo2', date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', premium='$premium', photo='$photo'  where lid=$lid");

    $comp_id     = jobs_getCompIdFromName($company);
    $extra_users = jobs_getThisCompany($comp_id, $usid);

    $extra_user1 = $extra_users['comp_user1'];
    $extra_user2 = $extra_users['comp_user2'];

    if ($extra_user1) {
        $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uid=$extra_user1");
        list($extra_user1_email) = $xoopsDB->fetchRow($result);
        $extra_user1_email = $extra_user1_email;
    } else {
        $extra_user1_email = '';
    }

    if ($extra_user2) {
        $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uid=$extra_user2");
        list($extra_user2_email) = $xoopsDB->fetchRow($result);
        $extra_user2_email = $extra_user2_email;
    } else {
        $extra_user2_email = '';
    }

    if ($email) {
        $tags                   = [];
        $tags['TITLE']          = $title;
        $tags['TYPE']           = $type;
        $tags['DESCTEXT']       = $desctext;
        $tags['SUBMITTER']      = $submitter;
        $tags['VEDIT_JOB']      = _AM_JOBS_EDIT_YOUR_JOB;
        $tags['YOUR_JOB']       = _AM_JOBS_YOUR_JOB;
        $tags['HELLO']          = _AM_JOBS_HELLO;
        $tags['YOUR_JOB_ON']    = _AM_JOBS_YOUR_JOB_ON;
        $tags['APPROVED']       = _AM_JOBS_APPROVED;
        $tags['LINK_URL']       = XOOPS_URL . '/modules/' . $moduleDirName . '/viewjobs.php?lid=' . $lid;
        $sql                    = 'SELECT title FROM ' . $xoopsDB->prefix('jobs_categories') . ' WHERE cid=' . $cid;
        $result                 = $xoopsDB->query($sql);
        $row                    = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_TITLE'] = $row['title'];
        $tags['CATEGORY_URL']   = XOOPS_URL . '/modules/' . $moduleDirName . '/jobscat.php?cid="' . $cid;
        $tags['THANKS']         = _AM_JOBS_THANK;
        $tags['WEBMASTER']      = _AM_JOBS_WEBMASTER;

        $subject = '' . _AM_JOBS_JOBACCEPT . '';
        $mail    = xoops_getMailer();

        if (is_dir('language/' . $xoopsConfig['language'] . '/mail_template/')) {
            $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $xoopsConfig['language'] . '/mail_template/');
        } else {
            $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/english/mail_template/");
        }

        $mail->setTemplate('jobs_listing_approve.tpl');
        $mail->useMail();
        $mail->multimailer->isHTML(true);
        $mail->setFromEmail($xoopsConfig['adminmail']);
        $mail->setToEmails([$email, $extra_user1_email, $extra_user2_email]);
        $mail->setSubject($subject);
        $mail->multimailer->isHTML(true);
        $mail->assign($tags);
        $mail->send();
        echo $mail->getErrors();
    }

    $notificationHandler = xoops_getHandler('notification');
    $notificationHandler->triggerEvent('global', 0, 'new_listing', $tags);
    $notificationHandler->triggerEvent('category', $cid, 'new_listing', $tags);
    $notificationHandler->triggerEvent('listing', $cid, 'new_listing', $tags);

    redirect_header('index.php', 3, _AM_JOBS_JOBVALID);
}

#  function Resume Valid
#####################################################
/**
 * @param $lid
 * @param $cat
 * @param $title
 * @param $status
 * @param $exp
 * @param $expire
 * @param $private
 * @param $tel
 * @param $salary
 * @param $typeprice
 * @param $date
 * @param $email
 * @param $submitter
 * @param $town
 * @param $state
 * @param $valid
 * @param $resume
 */
function ResumeValid(
    $lid,
    $cat,
    $title,
    $status,
    $exp,
    $expire,
    $private,
    $tel,
    $salary,
    $typeprice,
    $date,
    $email,
    $submitter,
    $town,
    $state,
    $valid,
    $resume
) {
    global $xoopsDB, $xoopsConfig, $myts, $meta, $moduleDirName, $admin_lang;

    $title     = $myts->addSlashes($title);
    $status    = $myts->addSlashes($status);
    $exp       = $myts->addSlashes($exp);
    $expire    = $myts->addSlashes($expire);
    $private   = $myts->addSlashes($private);
    $tel       = $myts->addSlashes($tel);
    $salary    = $myts->addSlashes($salary);
    $typeprice = $myts->addSlashes($typeprice);
    $submitter = $myts->addSlashes($submitter);
    $town      = $myts->addSlashes($town);
    $state     = $myts->addSlashes($state);
    $resume    = $myts->addSlashes($resume);

    $xoopsDB->query('update '
                    . $xoopsDB->prefix('jobs_resume')
                    . " set cid='$cat', title='$title', status='$status', exp='$exp', expire='$expire', private='$private', tel='$tel', salary='$salary', typeprice='$typeprice', date='$date', email='$email', submitter='$submitter', town='$town', state='$state', valid='$valid', resume='$resume'  where lid=$lid");

    if ('' == $email) {
    } else {
        $message = "$submitter " . _AM_JOBS_HELLO . "\n\n " . _AM_JOBS_RES_JOBACCEPT . " :\n\n $title\n\n\n " . _AM_JOBS_CONSULTTO . "\n " . XOOPS_URL . "/modules/$moduleDirName/viewresume.php?lid=$lid\n\n " . _AM_JOBS_THANK . "\n\n" . _AM_JOBS_TEAMOF . ' ' . $meta['title'] . "\n" . XOOPS_URL . '';
        $subject = '' . _AM_JOBS_RES_JOBACCEPT . '';
        $mail    = xoops_getMailer();
        $mail->useMail();
        $mail->setFromName($meta['title']);
        $mail->setFromEmail($xoopsConfig['adminmail']);
        $mail->setToEmails($email);
        $mail->setSubject($subject);
        $mail->setBody($message);
        $mail->send();
        echo $mail->getErrors();
    }
    redirect_header('index.php', 3, _AM_JOBS_RES_JOBVALID);
}

#  function ListingAddType
#####################################################
/**
 * @param $type
 */
function ListingAddType($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $moduleDirName, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('jobs_type') . " where nom_type='$type'"));
    if ($numrows > 0) {
        xoops_cp_header();
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
        echo '<br><center><b>' . _AM_JOBS_ERRORTYPE . " $nom_type " . _AM_JOBS_EXIST . '</b><br><br>';
        echo '<form method="post" action="main.php">
            <b>' . _AM_JOBS_ADDTYPE . '</b><br><br>
            ' . _AM_JOBS_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
            <input type="hidden" name="op" value="ListingAddType">
            <input type="submit" value="' . _AM_JOBS_ADD . '">
            </form>';
        echo '</td></tr></table>';
        require_once __DIR__ . '/admin_footer.php';
    } else {
        $type = $myts->addSlashes($type);

        if ('' == $type) {
            $type = '! ! ? ! !';
        }
        $xoopsDB->query('insert into ' . $xoopsDB->prefix('jobs_type') . " values (null, '$type')");

        redirect_header('main.php', 3, _AM_JOBS_ADDTYPE2);
    }
}

#  function ListingModType
#####################################################
/**
 * @param $id_type
 */
function ListingModType($id_type)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $moduleDirName, $admin_lang;

    xoops_cp_header();

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo '<b>' . _AM_JOBS_MODTYPE . '</b><br><br>';
    $result = $xoopsDB->query('select id_type, nom_type from ' . $xoopsDB->prefix('jobs_type') . " where id_type=$id_type");
    list($id_type, $nom_type) = $xoopsDB->fetchRow($result);

    $nom_type = $myts->htmlSpecialChars($nom_type);

    echo '<form action="main.php" method="post">';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo ''
         . _AM_JOBS_TYPE
         . " <input type=\"text\" name=\"nom_type\" value=\"$nom_type\" size=\"51\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_type\" value=\"$id_type\">"
         . '<input type="hidden" name="op" value="ListingModTypeS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _AM_JOBS_SAVMOD
         . '"></form></td><td>'
         . '<form action="main.php" method="post">'
         . "<input type=\"hidden\" name=\"id_type\" value=\"$id_type\">"
         . '<input type="hidden" name="op" value="ListingDelType">'
         . '<input type="submit" value="'
         . _AM_JOBS_DEL
         . '"></form></td></tr></table>';

    echo '</td></tr></table>';
    require_once __DIR__ . '/admin_footer.php';
}

#  function ListingModTypeS
#####################################################
/**
 * @param $id_type
 * @param $nom_type
 */
function ListingModTypeS($id_type, $nom_type)
{
    global $xoopsDB, $xoopsConfig, $myts, $moduleDirName, $admin_lang;

    $nom_type = $myts->addSlashes($nom_type);
    $xoopsDB->query('update ' . $xoopsDB->prefix('jobs_type') . " set nom_type='$nom_type' where id_type='$id_type'");
    redirect_header('main.php', 3, _AM_JOBS_TYPEMOD);
}

#  function ListingDelType
#####################################################
/**
 * @param $id_type
 */
function ListingDelType($id_type)
{
    global $xoopsDB, $moduleDirName, $admin_lang;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('jobs_type') . " where id_type='$id_type'");
    redirect_header('main.php', 3, _AM_JOBS_TYPEDEL);
}

#  function ListingAddprice
#####################################################
/**
 * @param $type
 */
function ListingAddprice($type)
{
    global $xoopsDB, $xoopsConfig, $myts, $moduleDirName, $admin_lang;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('select  COUNT(*)  FROM ' . $xoopsDB->prefix('jobs_price') . " where nom_price='$type'"));
    if ($numrows > 0) {
        xoops_cp_header();
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
        echo '<br><center><b>' . _AM_JOBS_ERRORPRICE . " $nom_price " . _AM_JOBS_EXIST . '</b><br><br>';
        echo '<form method="post" action="main.php">
            <b>' . _AM_JOBS_ADDPRICE . '</b><br><br>
            ' . _AM_JOBS_TYPE . '   <input type="text" name="type" size="30" maxlength="100">
            <input type="hidden" name="op" value="ListingAddprice">
            <input type="submit" value="' . _AM_JOBS_ADD . '">
            </form>';
        echo '</td></tr></table>';
        require_once __DIR__ . '/admin_footer.php';
    } else {
        $type = $myts->addSlashes($type);
        if ('' == $type) {
            $type = '! ! ? ! !';
        }
        $xoopsDB->query('insert into ' . $xoopsDB->prefix('jobs_price') . " values (null, '$type')");

        redirect_header('main.php', 3, _AM_JOBS_ADDPRICE2);
    }
}

#  function ListingModprice
#####################################################
//function ListingModprice($id_price, $nom_type)
/**
 * @param $id_price
 */
function ListingModprice($id_price)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $moduleDirName, $admin_lang;

    xoops_cp_header();

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo '<b>' . _AM_JOBS_MODPRICE . '</b><br><br>';
    $result = $xoopsDB->query('select nom_price from ' . $xoopsDB->prefix('jobs_price') . " where id_price=$id_price");
    list($nom_price) = $xoopsDB->fetchRow($result);

    $nom_price = $myts->htmlSpecialChars($nom_price);

    echo '<form action="main.php" method="post">';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo ''
         . _AM_JOBS_TYPE
         . " <input type=\"text\" name=\"nom_price\" value=\"$nom_price\" size=\"50\" maxlength=\"50\"><br>"
         . "<input type=\"hidden\" name=\"id_price\" value=\"$id_price\">"
         . '<input type="hidden" name="op" value="ListingModpriceS">'
         . '<table border="0"><tr><td>'
         . '<input type="submit" value="'
         . _AM_JOBS_SAVMOD
         . '"></form></td><td>'
         . '<form action="main.php" method="post">'
         . "<input type=\"hidden\" name=\"id_price\" value=\"$id_price\">"
         . '<input type="hidden" name="op" value="ListingDelprice">'
         . '<input type="submit" value="'
         . _AM_JOBS_DEL
         . '"></form></td></tr></table>';
    echo '</td></tr></table>';
    require_once __DIR__ . '/admin_footer.php';
}

#  function ListingModpriceS
#####################################################
/**
 * @param $id_price
 * @param $nom_price
 */
function ListingModpriceS($id_price, $nom_price)
{
    global $xoopsDB, $xoopsConfig, $myts, $moduleDirName, $admin_lang;

    $nom_price = $myts->addSlashes($nom_price);
    $xoopsDB->query('update ' . $xoopsDB->prefix('jobs_price') . " set nom_price='$nom_price' where id_price='$id_price'");
    redirect_header('main.php', 3, _AM_JOBS_PRICEMOD);
}

#  function ListingDelprice
#####################################################
/**
 * @param $id_price
 */
function ListingDelprice($id_price)
{
    global $xoopsDB, $moduleDirName, $admin_lang;

    $xoopsDB->query('delete from ' . $xoopsDB->prefix('jobs_price') . " where id_price='$id_price'");

    redirect_header('main.php', 3, _AM_JOBS_PRICEDEL);
}

#####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

$createres = \Xmf\Request::getString('createres', '', 'GET');
$ok        = \Xmf\Request::getString('ok', '', 'GET');

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = $_GET['lid'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {

    case 'IndexView':
        IndexView($lid);
        break;

    case 'IndexResumeView':
        IndexResumeView($lid);
        break;

    case 'ListingDelprice':
        ListingDelprice($id_price);
        break;

    case 'ListingModprice':
        ListingModprice($id_price);
        break;

    case 'ListingModpriceS':
        ListingModpriceS($id_price, $nom_price);
        break;

    case 'ListingAddprice':
        ListingAddprice($type);
        break;

    case 'ListingDelType':
        ListingDelType($id_type);
        break;

    case 'ListingModType':
        ListingModType($id_type);
        break;

    case 'ListingModTypeS':
        ListingModTypeS($id_type, $nom_type);
        break;

    case 'ListingAddType':
        ListingAddType($type);
        break;

    case 'ListingDel':
        ListingDel($lid);
        break;

    case 'ResumeDel':
        ResumeDel($lid, $resume);
        break;

    case 'ListingValid':
        ListingValid($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $usid, $town, $state, $valid, $premium, $photo);
        break;

    case 'ResumeValid':
        ResumeValid($lid, $cid, $title, $status, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $town, $state, $valid, $resume);
        break;

    case 'ModJob':
        ModJob($lid);
        break;

    case 'ModJobS':
        ModJobS($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $id_price, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $usid, $town, $state, $valid, $premium);
        break;

    default:
        Index();
        break;
}
