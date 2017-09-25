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

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * Class jobs_jobs_price
 */
class jobs_jobs_price extends XoopsObject
{
    //Constructor
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id_price', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('nom_price', XOBJ_DTYPE_TXTBOX, null, false, 150);
    }

    /**
     * @param bool $action
     *
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsDB, $xoopsModuleConfig;

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_AM_JOBS_JOBS_PRICE_ADD) : sprintf(_AM_JOBS_JOBS_PRICE_EDIT);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new XoopsFormText(_AM_JOBS_JOBS_PRICE_NOM_PRICE, 'nom_price', 50, 255, $this->getVar('nom_price')), false);

        $form->addElement(new XoopsFormHidden('op', 'save_jobs_price'));

        //Submit buttons
        $button_tray   = new XoopsFormElementTray('', '');
        $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $button_tray->addElement($submit_button);

        $cancel_button = new XoopsFormButton('', '', _CANCEL, 'cancel');
        $cancel_button->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($cancel_button);

        $form->addElement($button_tray);

        return $form;
    }
}

/**
 * Class jobsjobs_jobs_priceHandler
 */
class jobsjobs_jobs_priceHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'jobs_price', 'jobs_jobs_price', 'id_price', 'nom_price');
    }
}
