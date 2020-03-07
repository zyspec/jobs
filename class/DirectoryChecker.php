<?php

namespace XoopsModules\Jobs;

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

//// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Class DirectoryChecker
 * check status of a directory
 */
class DirectoryChecker
{
    /**
     * @param       $path
     * @param int   $mode
     * @param array $languageConstants
     * @param       $redirectFile
     *
     * @return bool|string
     */
    public static function getDirectoryStatus($path, $mode = 0777, $languageConstants = [], $redirectFile)
    {
        //global $pathIcon16;

        $languageConstants1 = [$languageConstants[5], $languageConstants[6]];
        $languageConstants2 = [$languageConstants[7], $languageConstants[8]];

        $myWords1 = urlencode(json_encode($languageConstants1));
        $myWords2 = urlencode(json_encode($languageConstants2));

        if (empty($path)) {
            return false;
        }
        if (!@is_dir($path)) {
            $path_status = "<img src='" . \Xmf\Module\Admin::iconUrl('0.png', '16') . "'>" . $path . ' ( ' . $languageConstants[1] . ' ) ' . '<a href=' . $_SERVER['PHP_SELF'] . "?op=createdir&amp;path=$path&amp;redirect=$redirectFile&amp;languageConstants=$myWords1>" . $languageConstants[2] . '</a>';
        } elseif (@is_writable($path)) {
            $path_status = "<img src='" . \Xmf\Module\Admin::iconUrl('1.png', '16') . "'>" . $path . ' ( ' . $languageConstants[0] . ' ) ';
            $currentMode = substr(decoct(fileperms($path)), 2);
            if ($currentMode != decoct($mode)) {
                $path_status = "<img src='"
                               . \Xmf\Module\Admin::iconUrl('0.png', '16') . "'> "
                               . $path
                               . sprintf($languageConstants[3], decoct($mode), $currentMode)
                               . '<a href='
                               . $_SERVER['PHP_SELF']
                               . "?op=setperm&amp;mode=$mode&amp;path=$path&amp;redirect=$redirectFile&amp;languageConstants=$myWords2> "
                               . $languageConstants[4]
                               . '</a>';
            }
        } else {
            $currentMode = substr(decoct(fileperms($path)), 2);
            $path_status = "<img src='"
                           . \Xmf\Module\Admin::iconUrl('0.png', '16') . "'> "
                           . $path
                           . sprintf($languageConstants[3], decoct($mode), $currentMode)
                           . '<a href='
                           . $_SERVER['PHP_SELF']
                           . "?op=setperm&amp;mode=$mode&amp;path=$path&amp;redirect=$redirectFile&amp;languageConstants=$myWords2> "
                           . $languageConstants[4]
                           . '</a>';
        }

        return $path_status;
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function createDirectory($target, $mode = 0777)
    {
        $target = str_replace('..', '', $target);

        // http://www.php.net/manual/en/function.mkdir.php
        return is_dir($target) || (self::createDirectory(dirname($target), $mode) and mkdir($target, $mode));
    }

    /**
     * @param     $target
     * @param int $mode
     *
     * @return bool
     */
    public static function setDirectoryPermissions($target, $mode = 0777)
    {
        $target = str_replace('..', '', $target);

        return @chmod($target, (int)$mode);
    }
}

$op = \Xmf\Request::getString('op', '', 'GET');

switch ($op) {
    case 'createdir':
        $languageConstants = [];
        if (isset($_GET['path'])) {
            $path = $_GET['path'];
        }
        if (isset($_GET['redirect'])) {
            $redirect = $_GET['redirect'];
        }
        if (isset($_GET['languageConstants'])) {
            $languageConstants = json_decode($_GET['languageConstants']);
        }
        $result = DirectoryChecker::createDirectory($path);
        $msg    = $result ? $languageConstants[0] : $languageConstants[1];
        redirect_header($redirect, 2, $msg . ': ' . $path);

        break;
    case 'setperm':
        $languageConstants = [];
        if (isset($_GET['path'])) {
            $path = $_GET['path'];
        }
        if (isset($_GET['mode'])) {
            $mode = $_GET['mode'];
        }
        if (isset($_GET['redirect'])) {
            $redirect = $_GET['redirect'];
        }
        if (isset($_GET['languageConstants'])) {
            $languageConstants = json_decode($_GET['languageConstants']);
        }
        $result = DirectoryChecker::setDirectoryPermissions($path, $mode);
        $msg    = $result ? $languageConstants[0] : $languageConstants[1];
        redirect_header($redirect, 2, $msg . ': ' . $path);

        break;
}
