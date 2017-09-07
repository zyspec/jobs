<?php
/**
 * @return array
 */
function b_waiting_jobs()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = [];

    // jobs listings
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_listing') . " WHERE valid='0'");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/jobs/admin/index.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_JOBS;
    }

    $ret[] = $block;

    $block = [];

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('jobs_resume') . " WHERE valid='0'");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/jobs/admin/index.php';
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_RESUMES;
    }

    $ret[] = $block;

    return $ret;
}
