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
//

use Xmf\Database;
use \Xmf\Request;
use \Xmf\Yaml;

require_once __DIR__ . '/admin_header.php';
//$thisFile = basename(__FILE__);

xoops_cp_header();
/**
 * @var \XoopsModules\Jobs\Helper $helper
 * @var \Xmf\Module\Admin $adminObject
 */
$adminObject->displayNavigation();
$adminObject->addItemButton(_AM_JOBS_ADD_REGION, $helper->url('admin/region.php?op=RegionAdd'), 'add', '');
$adminObject->displayButton('left', '');

echo "<fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_LISTS . '</legend>';
// get list of files in .yml directory
$ymlPath = $helper->path('admin/yaml/');

/** @var \XoopsFile $ymlFileObj */
$xoopsFileObj       = new \XoopsFile($ymlPath);
$xoopsFolderHandler = $xoopsFileObj->getHandler('folder', $ymlPath);

//$fileList = $xoopsFolderHandler->find('.*\.yml', true); //returns sorted array of .yml files
$fileList = $xoopsFolderHandler->find('.*\.ya?ml', true); //returns sorted array of .y[a]ml files
if (0 === count($fileList)) {
    $helper->redirect('admin/index.php', 3, 'Could not find any Regions/States to import');
}
// now read each file and get the region name (pid = 0)
$regionOptionArray = [];
foreach ($fileList as $ymlFileName){
    if (false !== $regionArray = Yaml::read($ymlPath . $ymlFileName)){
        foreach($regionArray as $region) {
            if (0 !== $region['pid']) {
                continue;
            }
            $regionOptionArray[$ymlFileName] = $region['name'];
        }
    } else {
        $helper->redirect('admin/index.php', 3, 'INVALID YAML DATA READ');
    }
}
$regionInp = Request::getString('region', '');
if ('' === $regionInp) {
    $form = new \XoopsThemeForm(_AM_JOBS_ADD_REGION, 'region_form', $helper->url('admin/lists.php', 'post', true));
    $formSelect = new \XoopsFormSelect(_AM_JOBS_SELECT_REGION, 'region');
    $formSelect->addOptionArray($regionOptionArray);
    $form->addElement($formSelect);
    $form->addElement(new \XoopsFormButtonTray('submit', _SUBMIT));
    $form->display();
} else {
    $ok = Request::getInt('ok', 0, 'POST');
    if (1 === $ok) {
        //check XoopsSecurity token
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $insertCount = Database\TableLoad::loadTableFromYamlFile('jobs_region', $ymlPath . $regionInp);
        $msg = (0 === $insertCount) ? _AM_JOBS_UPDATEFAILED : _AM_JOBS_REGION_ADDED;
        $helper->redirect('admin/region.php', 3, $msg);
    } else {
        xoops_confirm(['region' => $regionInp, 'ok' => 1], $helper->url('admin/lists.php'), _AM_JOBS_SUREADDREGIONS, _OK);
    }
}
echo '</fieldset>';
xoops_cp_footer();
