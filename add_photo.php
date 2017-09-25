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

$moduleDirName = basename(dirname(__DIR__));
$main_lang     = '_' . strtoupper($moduleDirName);
$lid           = !isset($_REQUEST['lid']) ? null : $_REQUEST['lid'];

/**
 * Xoops header ...
 */
include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'jobs_view_photos.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/**
 * Modules class includes
 */
include __DIR__ . '/class/pictures.php';

/**
 * Factory of pictures created
 */
$album_factory = new Xoopsjlm_picturesHandler($xoopsDB);

/**
 * Getting the title
 */
$title = $_POST['caption'];

/**
 * Getting parameters defined in admin side
 */

$path_upload   = $xoopsModuleConfig['jobs_path_upload'];
$pictwidth     = $xoopsModuleConfig['jobs_resized_width'];
$pictheight    = $xoopsModuleConfig['jobs_resized_height'];
$thumbwidth    = $xoopsModuleConfig['jobs_thumb_width'];
$thumbheight   = $xoopsModuleConfig['jobs_thumb_height'];
$maxfilebytes  = $xoopsModuleConfig['jobs_maxfilesize'];
$maxfileheight = $xoopsModuleConfig['jobs_max_original_height'];
$maxfilewidth  = $xoopsModuleConfig['jobs_max_original_width'];

/**
 * If we are receiving a file
 */
if ('sel_photo' === $_POST['xoops_upload_file'][0]) {

    /**
     * Check if using Xoops or XoopsCube (by jlm69)
     */

    $xCube = false;
    if (preg_match('/^XOOPS Cube/', XOOPS_VERSION)) { // XOOPS Cube 2.1x
        $xCube = true;
    }
    if ($xCube) {
        if (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['token'])) {
            redirect_header(XOOPS_URL . '/', 3, $GLOBALS['xoopsSecurity']->getErrors());
        }
    } else {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($_SERVER['HTTP_REFERER'], 3, constant($main_lang . '_TOKENEXPIRED'));
        }
    }
    /**
     * Try to upload picture resize it insert in database and then redirect to index
     */
    if ($album_factory->receivePicture($title, $path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes, $maxfilewidth, $maxfileheight)) {
        header('Location: ' . XOOPS_URL . "/modules/$moduleDirName/view_photos.php?lid=$lid&uid=" . $xoopsUser->getVar('uid'));

        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('jobs_resume') . ' SET rphoto=rphoto+1 WHERE lid = ' . $xoopsDB->escape($lid) . '');
    } else {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/view_photos.php?uid=" . $xoopsUser->getVar('uid'), 3, constant($main_lang . '_NOCACHACA'));
    }
}

/**
 * Close page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';
