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
 * @author      John Mordo
 * @author      XOOPS Development Team
 */

$img_number = imagecreate(275, 25);
$backcolor  = imagecolorallocate($img_number, 102, 102, 153);
$textcolor  = imagecolorallocate($img_number, 255, 255, 255);

imagefill($img_number, 0, 0, $backcolor);
$number = "$_SERVER[REMOTE_ADDR]";

imagestring($img_number, 10, 5, 5, $number, $textcolor);

header('Content-type: image/jpeg');
imagejpeg($img_number);
