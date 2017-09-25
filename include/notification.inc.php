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

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";
/**
 * @param $category
 * @param $item_id
 *
 * @return mixed
 */
function jobs_notify_iteminfo($category, $item_id)
{
    $moduleDirName = basename(dirname(__DIR__));
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname("$moduleDirName ");

    $item_id = (int)$item_id;

    if ('global' == $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsDB, $moduleDirName;

    if ('category' == $category) {
        // Assume we have a valid topid id
        $sql = 'SELECT title  FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_categories') . ' WHERE cid = ' . $item_id . ' LIMIT 1';
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header('index.php', 2, _MD_ERRORFORUM);
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $moduleDirName . '/jobscat.php?cid=' . $item_id;

        return $item;
    }
    if ('job_listing' == $category) {
        // Assume we have a valid post id
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_listing') . ' WHERE lid = ' . $item_id . ' LIMIT 1';
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header('index.php', 2, _MD_ERROROCCURED);
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . "$moduleDirName " . '/viewjobs.php?lid= ' . $item_id;

        return $item;
    }
    if ('company_listing' == $category) {
        $company_name = jobs_getCompNameFromId($item_id);

        // Assume we have a valid post id
        //      $sql = 'SELECT company FROM ' . $xoopsDB->prefix("".$moduleDirName ."_listing"). ' WHERE (`company` = '.$company_name.') LIMIT 1';
        if (!$company_name) {
            redirect_header('index.php', 12, _MD_ERROROCCURED);
        }
        //      $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $company_name;
        $item['url']  = XOOPS_URL . '/modules/jobs/members.php?comp_id=' . $item_id;

        return $item;
    }

    if ('res_global' == $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    if ('resume' == $category) {

        // Assume we have a valid topid id
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix('jobs_res_categories') . ' WHERE cid = ' . $item_id . ' LIMIT 1';
        //echo $sql;
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header('resumes.php', 2, _MD_ERROROCCURED);
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $moduleDirName . '/resumecat.php?cid=' . $item_id;

        return $item;
    }

    if ('resume_listing' == $category) {
        // Assume we have a valid post id
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_resume') . ' WHERE lid = ' . $item_id . ' LIMIT 1';
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header('resumes.php', 2, _MD_ERROROCCURED);
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $moduleDirName . '/viewresume.php?lid= ' . $item_id;

        return $item;
    }
}
