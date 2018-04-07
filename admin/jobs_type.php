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

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
$adminObject  = \Xmf\Module\Admin::getInstance();
$adminObject2 = \Xmf\Module\Admin::getInstance();
//It recovered the value of argument op in URL$
$op = jobs_CleanVars($_REQUEST, 'op', 'list', 'string');
switch ($op) {
    case 'list':
    default:

        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_JOBS_NEWJOBS_TYPE, 'jobs_type.php?op=new_jobs_type', 'add');
        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id_type');
        $criteria->setOrder('ASC');
        $jobsNumrows   = $typeHandler->getCount();
        $jobs_type_arr = $typeHandler->getAll($criteria);

        //Table view Jobs Type
        if ($jobsNumrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"center\">" . _AM_JOBS_JOBS_TYPE_NOM_TYPE . "</th>

                    <th align='center' width='10%'>" . _AM_JOBS_FORMACTION . '</th>
                </tr>';

            $class = 'odd';

            foreach (array_keys($jobs_type_arr) as $i) {
                if (0 == $jobs_type_arr[$i]->getVar('jobs_type_pid')) {
                    echo "<tr class='" . $class . "'>";
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo '<td align="center">' . $jobs_type_arr[$i]->getVar('nom_type') . '</td>';

                    echo "<td align='center' width='10%'>
                        <a href='jobs_type.php?op=edit_jobs_type&id_type=" . $jobs_type_arr[$i]->getVar('id_type') . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='jobs_type.php?op=delete_jobs_type&id_type=" . $jobs_type_arr[$i]->getVar('id_type') . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</table><br><br>';
        }

        //xoops_cp_header();

        $adminObject2->addItemButton(_AM_JOBS_NEWJOBS_PRICE, 'jobs_type.php?op=new_jobs_price', 'add');
        $adminObject2->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id_price');
        $criteria->setOrder('ASC');
        $priceNumrows   = $priceHandler->getCount();
        $jobs_price_arr = $priceHandler->getAll($criteria);

        //Table view
        if ($priceNumrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"center\">" . _AM_JOBS_JOBS_PRICE_NOM_PRICE . "</th>

                    <th align='center' width='10%'>" . _AM_JOBS_FORMACTION . '</th>
                </tr>';

            $class = 'odd';

            foreach (array_keys($jobs_price_arr) as $i) {
                if (0 == $jobs_price_arr[$i]->getVar('jobs_price_pid')) {
                    echo "<tr class='" . $class . "'>";
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo '<td align="center">' . $jobs_price_arr[$i]->getVar('nom_price') . '</td>';

                    echo "<td align='center' width='10%'>
                        <a href='jobs_type.php?op=edit_jobs_price&id_price=" . $jobs_price_arr[$i]->getVar('id_price') . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='jobs_type.php?op=delete_jobs_price&id_price=" . $jobs_price_arr[$i]->getVar('id_price') . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</table><br><br>';
        }

        break;

    case 'new_jobs_type':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_JOBS_JOBS_TYPELIST, 'jobs_type.php?op=list', 'list');
        $adminObject->displayButton('left');

        $obj  = $typeHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'save_jobs_type':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('jobs_type.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['id_type'])) {
            $obj = $typeHandler->get($_REQUEST['id_type']);
        } else {
            $obj = $typeHandler->create();
        }

        //Form nom_type
        $obj->setVar('nom_type', $_REQUEST['nom_type']);

        if ($typeHandler->insert($obj)) {
            redirect_header('jobs_type.php?op=list', 2, _AM_JOBS_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit_jobs_type':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_JOBS_NEWJOBS_TYPE, 'jobs_type.php?op=new_jobs_type', 'add');
        $adminObject->addItemButton(_AM_JOBS_JOBS_TYPELIST, 'jobs_type.php?op=list', 'list');
        $adminObject->displayButton('left');
        $obj  = $typeHandler->get($_REQUEST['id_type']);
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_jobs_type':
        $obj = $typeHandler->get($_REQUEST['id_type']);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('jobs_type.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($typeHandler->delete($obj)) {
                redirect_header('jobs_type.php', 3, _AM_JOBS_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id_type' => $_REQUEST['id_type'], 'op' => 'delete_jobs_type'], $_SERVER['REQUEST_URI'], sprintf(_AM_JOBS_FORMSUREDEL, $obj->getVar('jobs_type')));
        }
        break;

    case 'new_jobs_price':
        $adminObject2->displayNavigation(basename(__FILE__));
        $adminObject2->addItemButton(_AM_JOBS_JOBS_PRICELIST, 'jobs_type.php?op=list', 'list');
        $adminObject2->displayButton('left');

        $obj  = $priceHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'save_jobs_price':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('jobs_type.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['id_price'])) {
            $obj = $priceHandler->get($_REQUEST['id_price']);
        } else {
            $obj = $priceHandler->create();
        }

        //Form nom_price
        $obj->setVar('nom_price', $_REQUEST['nom_price']);

        if ($priceHandler->insert($obj)) {
            redirect_header('jobs_type.php?op=list', 2, _AM_JOBS_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit_jobs_price':
        $adminObject2->displayNavigation(basename(__FILE__));
        $adminObject2->addItemButton(_AM_JOBS_NEWJOBS_PRICE, 'jobs_type.php?op=new_jobs_price', 'add');
        $adminObject2->addItemButton(_AM_JOBS_JOBS_PRICELIST, 'jobs_type.php?op=list', 'list');
        $adminObject2->displayButton('left');
        $obj  = $priceHandler->get($_REQUEST['id_price']);
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_jobs_price':
        $obj = $priceHandler->get($_REQUEST['id_price']);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('jobs_type.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($priceHandler->delete($obj)) {
                redirect_header('jobs_type.php', 3, _AM_JOBS_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id_price' => $_REQUEST['id_price'], 'op' => 'delete_jobs_price'], $_SERVER['REQUEST_URI'], sprintf(_AM_JOBS_FORMSUREDEL, $obj->getVar('jobs_price')));
        }
        break;

}
require_once __DIR__ . '/admin_footer.php';
