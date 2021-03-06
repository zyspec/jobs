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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * @param $options
 *
 * @return array
 */
function jobs_b2_show($options)
{
    global $xoopsDB, $blockdirname, $block_lang;

    $block = [];
    $myts  = \MyTextSanitizer::getInstance();

    $blockdirname = basename(dirname(__DIR__));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    require_once XOOPS_ROOT_PATH . "/modules/$blockdirname/include/functions.php";

    $block['title'] = '' . constant($block_lang . '_TITLE3') . '';

    // To make the Compant Logo's Scroll up go to
    // your templates/blocks/jobs_b2.tpl file and
    // at the top add    <{$block.scroll}>
    $block['scroll'] = '<marquee scrollamount="2" behavior="scroll" direction="up" onMouseOver="marqid.stop()" onMouseOut="marqid.start()">';

    $cat_perms  = '';
    $categories = jobs_MygetItemIds('jobs_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND l.cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query('SELECT c.comp_id, c.comp_name, c.comp_img, l.company FROM ' . $xoopsDB->prefix('jobs_companies') . ' AS c LEFT OUTER JOIN ' . $xoopsDB->prefix('jobs_listing') . " AS l on c.comp_name = l.company WHERE l.valid = '1' and l.status!='0' $cat_perms GROUP BY c.comp_img");

    while (false !== (list($comp_id, $comp_name, $comp_img, $company) = $xoopsDB->fetchRow($result))) {
        $company   = $myts->undoHtmlSpecialChars($company);
        $comp_img  = $myts->htmlSpecialChars($comp_img);
        $comp_name = $myts->undoHtmlSpecialChars($comp_name);
        $a_item    = [];

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($comp_name) >= $options[2]) {
                $title = $myts->undoHtmlSpecialChars(substr($comp_name, 0, $options[2] - 1)) . '...';
            }
        }

        $a_item['company'] = $company;

        if ('' != $comp_img) {
            $a_item['logo_link'] = '<a href="' . XOOPS_URL . "/modules/$blockdirname/members.php?comp_id=" . $comp_id . '" ><img src="' . XOOPS_URL . "/modules/$blockdirname/logo_images/$comp_img\" alt=\"$comp_name\"  width=\"120px\"></a>";
        } else {
            $a_item['logo_link'] = '';
        }

        $block['items'][] = $a_item;
    }
    $block['link'] = '<a href="' . XOOPS_URL . "/modules/$blockdirname/\"><b>" . constant($block_lang . '_ALLANN2') . '</b></a></div>';
    $block['add']  = '<a href="' . XOOPS_URL . "/modules/$blockdirname/\"><b>" . constant($block_lang . '_ADDNOW') . '</b></a></div>';

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function jobs_b2_edit($options)
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
    $form .= "<option value='hits'";
    if ('hits' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . constant($block_lang . '_HITS') . '</option>';
    $form .= "</select>\n";
    $form .= '&nbsp;' . constant($block_lang . '_DISP') . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . constant($block_lang . '_LISTINGS');
    $form .= '&nbsp;<br><br>' . constant($block_lang . '_CHARS') . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . constant($block_lang . '_LENGTH') . '<br><br>';

    return $form;
}
