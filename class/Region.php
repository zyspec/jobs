<?php namespace XoopsModules\Jobs;

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
 * Class Region
 */
class Region extends \XoopsObject
{
    //Constructor
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('rid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('pid', XOBJ_DTYPE_INT, 0, false, 5);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 40);
        $this->initVar('abbrev', XOBJ_DTYPE_TXTBOX, null, true, 2);
    }
}
