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
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //

/**
 * @param $options
 *
 * @return array
 */
function jobs_show($options)
{
    global $xoopsDB, $blockdirname, $block_lang;

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $blockdirname = basename(dirname(__DIR__));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    require_once XOOPS_ROOT_PATH . "/modules/$blockdirname/include/functions.php";

    $block['title'] = '' . constant($block_lang . '_TITLE') . '';

    $cat_perms  = '';
    $categories = jobs_MygetItemIds('jobs_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query('SELECT lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, date, email, submitter, usid, town, state, valid, photo, view FROM '
                              . $xoopsDB->prefix('' . $blockdirname . '_listing')
                              . " WHERE valid='1' and status!='0' $cat_perms ORDER BY "
                              . $options[0]
                              . ' DESC', $options[1], 0);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $a_item = [];

        $cat_id    = jobs_getCompIdFromName($myrow['company']);
        $cat_name  = jobs_getCatNameFromId($myrow['cid']);
        $title     = $myts->undoHtmlSpecialChars($myrow['title']);
        $status    = $myts->htmlSpecialChars($myrow['status']);
        $expire    = $myts->htmlSpecialChars($myrow['expire']);
        $type      = $myts->htmlSpecialChars($myrow['type']);
        $company   = $myts->undoHtmlSpecialChars($myrow['company']);
        $price     = $myts->htmlSpecialChars($myrow['price']);
        $typeprice = $myts->htmlSpecialChars($myrow['typeprice']);
        $submitter = $myts->htmlSpecialChars($myrow['submitter']);
        $town      = $myts->htmlSpecialChars($myrow['town']);
        $state     = $myts->htmlSpecialChars($myrow['state']);
        $view      = $myts->htmlSpecialChars($myrow['view']);

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = $myts->htmlSpecialChars(substr($myrow['title'], 0, $options[2] - 1)) . '...';
            }
        }

        $a_item['title']        = $title;
        $a_item['cat_name']     = $cat_name;
        $a_item['company']      = $company;
        $a_item['type']         = $type;
        $a_item['expire']       = $expire;
        $a_item['price']        = $price;
        $a_item['typeprice']    = $typeprice;
        $a_item['typeprice']    = $typeprice;
        $a_item['submitter']    = $submitter;
        $a_item['town']         = $town;
        $a_item['state']        = $state;
        $a_item['view']         = $view;
        $a_item['id']           = $myrow['lid'];
        $a_item['cid']          = $myrow['cid'];
        $a_item['company_link'] = '<a href="' . XOOPS_URL . "/modules/$blockdirname/members.php?comp_id=" . $cat_id . "\"><b>$company</b></a>";
        $a_item['link']         = '<a href="' . XOOPS_URL . "/modules/$blockdirname/viewjobs.php?lid=" . addslashes($myrow['lid']) . "\"><b>$title</b></a>";
        $a_item['date']         = formatTimestamp($myrow['date'], 's');

        $block['items'][] = $a_item;
    }
    $block['lang_title']     = constant($block_lang . '_ITEM');
    $block['lang_salary']    = constant($block_lang . '_SALARY');
    $block['lang_typeprice'] = constant($block_lang . '_TYPEPRICE');
    $block['lang_date']      = constant($block_lang . '_DATE');
    $block['lang_local']     = constant($block_lang . '_LOCAL2');
    $block['lang_hits']      = constant($block_lang . '_HITS');
    $block['link']           = '<a href="' . XOOPS_URL . "/modules/$blockdirname/index.php\"><b>" . constant($block_lang . '_ALLANN2') . '</b></a></div>';
    $block['add']            = '<a href="' . XOOPS_URL . "/modules/$blockdirname/index.php\"><b>" . constant($block_lang . '_ADDNOW') . '</b></a></div>';

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function jobs_edit($options)
{
    global $xoopsDB;
    $blockdirname = basename(dirname(__DIR__));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    $form = constant($block_lang . '_ORDER') . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date'";
    if ('date' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . constant($block_lang . '_DATE') . "</option>\n";
    $form .= "<option value='view'";
    if ('view' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . constant($block_lang . '_HITS') . '</option>';
    $form .= "</select>\n";
    $form .= '&nbsp;' . constant($block_lang . '_DISP') . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . constant($block_lang . '_LISTINGS');
    $form .= '&nbsp;<br><br>' . constant($block_lang . '_CHARS') . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . constant($block_lang . '_LENGTH') . '<br><br>';

    return $form;
}
