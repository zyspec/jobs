<?php namespace XoopsModules\Jobs;

// $Id: grouppermform.php,v 1.14.22.1 2005/08/15 15:04:58 skalpa Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000-2003 XOOPS.org                           //
// <https://xoops.org>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, https://xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

use XoopsModules\Jobs;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined.');
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelement.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formhidden.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formbutton.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelementtray.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/form.php';


/**
 * Renders checkbox options for a group permission form
 *
 * @author     Kazumi Ono <onokazu@myweb.ne.jp>
 * @copyright  copyright (c) 2000-2003 XOOPS.org
 * @package    kernel
 * @subpackage form
 */
class MyGroupFormCheckBox extends \XoopsFormElement
{
    /**
     * Pre-selected value(s)
     *
     * @var array;
     */
    public $_value = [];
    /**
     * Group ID
     *
     * @var int
     */
    public $_groupId;
    /**
     * Option tree
     *
     * @var array
     */
    public $_optionTree;

    /**
     * Constructor
     * @param      $caption
     * @param      $name
     * @param      $groupId
     * @param null $values
     */
    public function __construct($caption, $name, $groupId, $values = null)
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($values)) {
            $this->setValue($values);
        }
        $this->_groupId = $groupId;
    }

    /**
     * Sets pre-selected values
     *
     * @param mixed $value A group ID or an array of group IDs
     * @access public
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->setValue($v);
            }
        } else {
            $this->_value[] = $value;
        }
    }

    /**
     * Sets the tree structure of items
     *
     * @param array $optionTree
     * @access public
     */
    public function setOptionTree(&$optionTree)
    {
        $this->_optionTree =& $optionTree;
    }

    /**
     * Renders checkbox options for this group
     *
     * @return string
     * @access public
     */
    public function render()
    {
        $ret  = '<table class="outer"><tr><td class="odd"><table><tr>';
        $cols = 1;
        foreach ($this->_optionTree[0]['children'] as $topitem) {
            if ($cols > 4) {
                $ret .= '</tr><tr>';
                $cols = 1;
            }
            $tree   = '<td>';
            $prefix = '';
            $this->_renderOptionTree($tree, $this->_optionTree[$topitem], $prefix);
            $ret .= $tree . '</td>';
            $cols++;
        }
        $ret .= '</tr></table></td><td class="even">';
        foreach (array_keys($this->_optionTree) as $id) {
            if (!empty($id)) {
                $option_ids[] = "'" . $this->getName() . '[groups][' . $this->_groupId . '][' . $id . ']' . "'";
            }
        }
        $checkallbtn_id = $this->getName() . '[checkallbtn][' . $this->_groupId . ']';
        $option_ids_str = implode(', ', $option_ids);
        $ret .= _ALL . ' <input id="' . $checkallbtn_id
                . '" type="checkbox" value="" onclick="var optionids = new Array(' . $option_ids_str
            . "); xoopsCheckAllElements(optionids, '" . $checkallbtn_id . "');\" >";
        $ret .= '</td></tr></table>';

        return $ret;
    }

    /**
     * Renders checkbox options for an item tree
     *
     * @param string $tree
     * @param array  $option
     * @param string $prefix
     * @param array  $parentIds
     * @access private
     */
    public function _renderOptionTree(&$tree, $option, $prefix, $parentIds = [])
    {
        $tree .= $prefix . '<input type="checkbox" name="' . $this->getName() . '[groups][' . $this->_groupId . ']['
            . $option['id'] . ']" id="' . $this->getName() . '[groups][' . $this->_groupId . '][' . $option['id']
                 . ']" onclick="';
        // If there are parent elements, add javascript that will
        // make them selecteded when this element is checked to make
        // sure permissions to parent items are added as well.
        foreach ($parentIds as $pid) {
            $parent_ele = $this->getName() . '[groups][' . $this->_groupId . '][' . $pid . ']';
            $tree .= "var ele = xoopsGetElementById('" . $parent_ele
                . "'); if(ele.checked !== true) {ele.checked = this.checked;}";
        }
        // If there are child elements, add javascript that will
        // make them unchecked when this element is unchecked to make
        // sure permissions to child items are not added when there
        // is no permission to this item.
        foreach ($option['allchild'] as $cid) {
            $child_ele = $this->getName() . '[groups][' . $this->_groupId . '][' . $cid . ']';
            $tree
                .=
                "var ele = xoopsGetElementById('" . $child_ele . "'); if(this.checked !== true) {ele.checked = false;}";
        }
        $tree .= '" value="1"';
        if (in_array($option['id'], $this->_value)) {
            $tree .= ' checked';
        }
        $tree
            .=
            ' >' . $option['name'] . '<input type="hidden" name="' . $this->getName() . '[parents][' . $option['id']
            . ']" value="' . implode(':', $parentIds) . '" ><input type="hidden" name="' . $this->getName()
            . '[itemname][' . $option['id'] . ']" value="' . htmlspecialchars($option['name'], ENT_QUOTES | ENT_HTML5) . "\" ><br >\n";
        if (isset($option['children'])) {
            foreach ($option['children'] as $child) {
                array_push($parentIds, $option['id']);
                $this->_renderOptionTree($tree, $this->_optionTree[$child], $prefix . '&nbsp;-', $parentIds);
            }
        }
    }
}
