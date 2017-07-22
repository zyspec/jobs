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

require_once __DIR__ . '/../../../include/cp_header.php';
$moduleDirName = basename(dirname(__DIR__));
$doc_lang      = '_DOC_' . strtoupper($moduleDirName);

if (file_exists(__DIR__ . '/../language/' . $xoopsConfig['language'] . '/docs.php')) {
    include __DIR__ . '/../language/' . $xoopsConfig['language'] . '/docs.php';
} else {
    include __DIR__ . '/../language/english/docs.php';
}

global $mytree, $xoopsDB, $xoopsModuleConfig, $moduleDirName;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
//loadModuleAdminMenu(4, "");
echo "<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . constant($doc_lang . '_DOCUMENTATION') . ' <br><br></legend>';
echo '<b>' . constant($doc_lang . '_VERSION') . '</b><br><br>
<b>' . constant($doc_lang . '_COMPANY_DOCS') . '</b><br><br>
<br>' . constant($doc_lang . '_DOC_1') . '
<br>
<br>' . constant($doc_lang . '_DOC_2') . '
<br>
<br>
' . constant($doc_lang . '_DOC_3') . '
<br><br>
' . constant($doc_lang . '_DOC_4') . '<br>
<br>

<br><br>
<br>
<br><br><br>';

echo '<br></fieldset><br>';
xoops_cp_footer();
