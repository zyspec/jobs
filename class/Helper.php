<?php

namespace XoopsModules\Jobs;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package    \XoopsModules\Jobs
 * @copyright  XOOPS Project https://xoops.org/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author     XOOPS Development Team
 * @link       https://github.com/XoopsModules25x/jobs
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Helper
 */
class Helper extends \Xmf\Module\Helper
{
    /**
     * @param bool $debug true, debug on | false, debug off
     */
    public $debug = false;

    /**
     * @param bool $debug
     */
    public function __construct($dirname = null)
    {
        if (null === $dirname) {
            $dirname = basename(dirname(__DIR__));
            $this->dirname = $dirname;
        }
        parent::__construct($dirname);
    }

    /**
     * @param string $dirname module directory name
     *
     * @return \XoopsModules\Jobs\Helper
     */
    public static function getInstance($dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($dirname);
        }
        return $instance;
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|\XoopsObjectHandler|\XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $db    = \XoopsDatabaseFactory::getDatabaseConnection();
        $class = __NAMESPACE__ . '\\' . ucfirst($name) . 'Handler';
        return new $class($db);
    }
}
