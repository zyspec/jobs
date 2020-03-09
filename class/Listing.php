<?php

namespace XoopsModules\Jobs;

/*
 * Jobs for XOOPS
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * @package     \XoopsModules\Jobs
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author      John Mordo aka jlm69 (www.jlmzone.com )
 * @author      XOOPS Development Team
 * @link        https://github.com/XoopsModules25x/jobs
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Listing Class
 */
class Listing extends \XoopsObject
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('status', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('expire', XOBJ_DTYPE_TXTBOX, null, false, 3);
        $this->initVar('type', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('company', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('desctext', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('requirements', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('tel', XOBJ_DTYPE_TXTBOX, null, false, 30);
        $this->initVar('price', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('typeprice', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('contactinfo', XOBJ_DTYPE_TXTAREA, null, true);
        $this->initVar('contactinfo1', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('contactinfo2', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('date', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('email', XOBJ_DTYPE_EMAIL, null, true);
        $this->initVar('submitter', XOBJ_DTYPE_TXTBOX, null, true, 60);
        $this->initVar('usid', XOBJ_DTYPE_TXTBOX, null, true, 6);
        $this->initVar('town', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('state', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('valid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('premium', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('photo', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('view', XOBJ_DTYPE_TXTBOX, null, false, 10);
    }

    /**
     * Magic function to return object title
     *
     * @return string
     */
    public function __toString() {
        return $this->getVar('title');
    }
}
