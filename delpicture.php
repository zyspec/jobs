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

use Xmf\Request;

/**
 * Xoops Header
 */
include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'jobs_index2.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/criteria.php';

/**
 * Module classes
 */

/**
 * Check if using Xoops or XoopsCube (by jlm69)
 * Right now Xoops does not have a directory called preload, Xoops Cube does.
 * If this finds a directory called preload in the Xoops Root folder $xCube=true.
 * This will need to change if Xoops adds a Directory called preload.
 */

$xCube = false;
if (preg_match('/^XOOPS Cube/', XOOPS_VERSION)) { // XOOPS Cube 2.1x
    $xCube = true;
}

/**
 * Verify Ticket for Xoops Cube (by jlm69)
 * If your site is XoopsCube it uses $xoopsGTicket for the token.
 */

if ($xCube) {
    if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
        redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 3, $GLOBALS['xoopsSecurity']->getErrors());
    }
} else {
    /**
     * Verify TOKEN for Xoops
     * If your site is Xoops it uses xoopsSecurity for the token.
     */
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 3, constant($main_lang . '_TOKENEXPIRED'));
    }
}

/**
 * Receiving info from get parameters
 */
$cod_img = $_POST['cod_img'];

/**
 * Creating the factory  and the criteria to delete the picture
 * The user must be the owner
 */
$album_factory = new Jobs\PicturesHandler($xoopsDB);
$criteria_img  = new \Criteria('cod_img', $cod_img);
$uid           = $xoopsUser->getVar('uid');
$criteria_uid  = new \Criteria('uid_owner', $uid);
$criteria_lid  = new \Criteria('lid', $lid);
$criteria      = new \CriteriaCompo($criteria_img);
$criteria->add($criteria_uid);

/**
 * Try to delete
 */
if ($album_factory->deleteAll($criteria)) {
    $lid = $_POST['lid'];
    $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('' . $moduleDirName . '_resume') . " SET rphoto=rphoto-1 WHERE lid='$lid'");
    redirect_header('view_photos.php?lid=' . $lid . '&uid=' . $uid . '', 3, constant($main_lang . '_DELETED'));
} else {
    redirect_header('view_photos.php?lid=' . $lid . '&uid=' . $uid . '', 3, constant($main_lang . '_NOCACHACA'));
}

/**
 * Close page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';
