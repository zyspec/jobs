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


use XoopsModules\Jobs;

/**
 * Protection against inclusion outside the site
 */
// defined('XOOPS_ROOT_PATH') || die('Restricted access');
$moduleDirName = basename(dirname(__DIR__));
$main_lang     = '_' . strtoupper($moduleDirName);
/**
 * Includes of form objects and uploader
 */
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/functions.php";


// -------------------------------------------------------------------------
// ------------------jlm_pictures user handler class -------------------
// -------------------------------------------------------------------------

/**
 * jlm_pictureshandler class.
 * This class provides simple mechanism for jlm_pictures object and generate forms for inclusion etc
 */
class PicturesHandler extends \XoopsObjectHandler
{
    /**
     * create a new Jobs\Pictures
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return \XoopsObject jlm_pictures
     */
    public function create($isNew = true)
    {
        $jlm_pictures = new Jobs\Pictures();
        if ($isNew) {
            $jlm_pictures->setNew();
        } else {
            $jlm_pictures->unsetNew();
        }

        return $jlm_pictures;
    }

    /**
     * retrieve a jlm_pictures
     *
     * @param int $id of the jlm_pictures
     * @param     $lid
     *
     * @return mixed reference to the {@link jlm_pictures} object, false if failed
     */
    public function &get($id, $lid)
    {
        global $moduleDirName;

        $sql = 'SELECT * FROM ' . $this->db->prefix('jobs_pictures') . ' WHERE cod_img=' . $id . ' AND lid=' . $lid . '';
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $jlm_pictures = new Jobs\Pictures();
            $jlm_pictures->assignVars($this->db->fetchArray($result));

            return $jlm_pictures;
        }

        return false;
    }

    /**
     * insert a new Jobs\Pictures in the database
     *
     * @param XoopsObject $jlm_pictures        reference to the {@link jlm_pictures}
     *                                         object
     * @param bool        $force
     * @return bool false if failed, true if already present and unchanged or successful
     */
    public function insert(\XoopsObject $jlm_pictures, $force = false)
    {
        global $xoopsConfig, $lid, $moduleDirName;
        if (!$jlm_pictures instanceof \jlm_pictures) {
            return false;
        }
        if (!$jlm_pictures->isDirty()) {
            return true;
        }
        if (!$jlm_pictures->cleanVars()) {
            return false;
        }
        foreach ($jlm_pictures->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = time();
        if ($jlm_pictures->isNew()) {
            // ajout/modification d'un jlm_pictures
            $jlm_pictures = new Jobs\Pictures();

            $format = 'INSERT INTO `%s` (cod_img, title, date_added, date_modified, lid, uid_owner, url)';
            $format .= 'VALUES (%u, %s, %s, %s, %s, %s, %s)';
            $sql    = sprintf($format, $this->db->prefix('jobs_pictures'), $cod_img, $this->db->quoteString($title), $now, $now, $this->db->quoteString($lid), $this->db->quoteString($uid_owner), $this->db->quoteString($url));
            $force  = true;
        } else {
            $format = 'UPDATE `%s` SET ';
            $format .= 'cod_img=%u, title=%s, date_added=%s, date_modified=%s, lid=%s, uid_owner=%s, url=%s';
            $format .= ' WHERE cod_img = %u';
            $sql    = sprintf($format, $this->db->prefix('jobs_pictures'), $cod_img, $this->db->quoteString($title), $now, $now, $this->db->quoteString($lid), $this->db->quoteString($uid_owner), $this->db->quoteString($url), $cod_img);
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($cod_img)) {
            $cod_img = $this->db->getInsertId();
        }
        $jlm_pictures->assignVar('cod_img', $cod_img);
        $jlm_pictures->assignVar('url', $url);

        return true;
    }

    /**
     * delete a jlm_pictures from the database
     *
     * @param XoopsObject $jlm_pictures reference to the jlm_pictures to delete
     * @param bool        $force
     *
     * @return bool false if failed.
     */
    public function delete(\XoopsObject $jlm_pictures, $force = false)
    {
        global $moduleDirName;

        if (!$jlm_pictures instanceof \jlm_pictures) {
            return false;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE cod_img = %u', $this->db->prefix('jobs_pictures'), $jlm_pictures->getVar('cod_img'));
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * retrieve jlm_pictures from the database
     *
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @return array array of {@link jlm_pictures} objects
     */
    public function &getObjects(CriteriaElement $criteria = null, $id_as_key = false)
    {
        global $moduleDirName;

        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('jobs_pictures');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $jlm_pictures = new Jobs\Pictures();
            $jlm_pictures->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $jlm_pictures;
            } else {
                $ret[$myrow['cod_img']] = $jlm_pictures;
            }
            unset($jlm_pictures);
        }

        return $ret;
    }

    /**
     * count jlm_pictures matching a condition
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int count of jlm_pictures
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        global $moduleDirName;

        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('jobs_pictures');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * delete jlm_pictures matching a set of conditions
     *
     * @param CriteriaElement $criteria {@link CriteriaElement}
     *
     * @return bool false if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null)
    {
        global $moduleDirName;
        $sql = 'DELETE FROM ' . $this->db->prefix('jobs_pictures');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Render a form to send pictures
     *
     * @param        $uid
     * @param        $lid
     * @param int    $maxbytes the maximum size of a picture
     * @param Smarty $xoopsTpl the one in which the form will be rendered
     *
     * @return bool true
     *
     * obs: Some functions wont work on php 4 so edit lines down under acording to your version
     */
    public function renderFormSubmit($uid, $lid, $maxbytes, $xoopsTpl)
    {
        global $moduleDirName, $main_lang, $xoopsUser;
        $form       = new \XoopsThemeForm(_JOBS_SUBMIT_PIC_TITLE, 'form_picture', "add_photo.php?lid=$lid&uid=" . $xoopsUser->getVar('uid') . '', 'post', true);
        $field_url  = new \XoopsFormFile(_JOBS_SELECT_PHOTO, 'sel_photo', 2000000);
        $field_desc = new \XoopsFormText(_JOBS_CAPTION, 'caption', 35, 55);
        $form->setExtra('enctype="multipart/form-data"');
        $button_send   = new \XoopsFormButton('', 'submit_button', _JOBS_UPLOADPICTURE, 'submit');
        $field_warning = new \XoopsFormLabel(sprintf(_JOBS_YOUCANUPLOAD, $maxbytes / 1024));
        $field_lid     = new \XoopsFormHidden('lid', $lid);
        $field_uid     = new \XoopsFormHidden('uid', $uid);

        $GLOBALS['xoopsSecurity']->getTokenHTML();

        $form->addElement($field_warning);
        $form->addElement($field_url, true);
        $form->addElement($field_desc, true);
        $form->addElement($field_lid, true);
        $form->addElement($field_uid, true);
//        $form->addElement($field_token, true);
        $form->addElement($button_send);
        if (str_replace('.', '', PHP_VERSION) > 499) {
            $form->assign($xoopsTpl);
        } else {
            $form->display();
        }

        return true;
    }

    /**
     * Render a form to edit the description of the pictures
     *
     * @param string $caption  The description of the picture
     * @param int    $cod_img  the id of the image in database
     * @param text   $filename the url to the thumb of the image so it can be displayed
     *
     * @return bool true
     */
    public function renderFormEdit($caption, $cod_img, $filename)
    {
        global $moduleDirName, $main_lang;

        $form       = new \XoopsThemeForm(_JOBS_EDIT_CAPTION, 'form_picture', 'editdesc.php', 'post', true);
        $field_desc = new \XoopsFormText($caption, 'caption', 35, 55);
        $form->setExtra('enctype="multipart/form-data"');
        $button_send   = new \XoopsFormButton(_JOBS_EDIT, 'submit_button', 'Submit', 'submit');
        $field_warning = new \XoopsFormLabel("<img src='" . $filename . "' alt='sssss'>");
        $field_cod_img = new \XoopsFormHidden('cod_img', $cod_img);
        $field_lid     = new \XoopsFormHidden('lid', $lid);
        $field_marker  = new \XoopsFormHidden('marker', 1);


        $GLOBALS['xoopsSecurity']->getTokenHTML();

        $form->addElement($field_warning);
        $form->addElement($field_desc);
        $form->addElement($field_cod_img);
        $form->addElement($field_marker);
        $form->addElement($button_send);
        $form->display();

        return true;
    }

    /**
     * Upload the file and Save into database
     *
     * @param text $title         A litle description of the file
     * @param text $path_upload   The path to where the file should be uploaded
     * @param int  $thumbwidth    the width in pixels that the thumbnail will have
     * @param int  $thumbheight   the height in pixels that the thumbnail will have
     * @param int  $pictwidth     the width in pixels that the pic will have
     * @param int  $pictheight    the height in pixels that the pic will have
     * @param int  $maxfilebytes  the maximum size a file can have to be uploaded in bytes
     * @param int  $maxfilewidth  the maximum width in pixels that a pic can have
     * @param int  $maxfileheight the maximum height in pixels that a pic can have
     *
     * @return bool false if upload fails or database fails
     */
    public function receivePicture(
        $title,
        $path_upload,
        $thumbwidth,
        $thumbheight,
        $pictwidth,
        $pictheight,
        $maxfilebytes,
        $maxfilewidth,
        $maxfileheight
    ) {
        global $xoopsUser, $xoopsDB, $_POST, $_FILES, $lid;
        //busca id do user logado
        $uid = $xoopsUser->getVar('uid');
        $lid = $_POST['lid'];
        //create a hash so it does not erase another file
        $hash1 = time();
        $hash  = substr($hash1, 0, 4);
        // mimetypes and settings put this in admin part later
        $allowed_mimetypes = ['image/jpeg', 'image/pjpeg'];
        $maxfilesize       = $maxfilebytes;
        // create the object to upload
        $uploader = new \XoopsMediaUploader($path_upload, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        // fetch the media
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            //lets create a name for it
            $uploader->setPrefix('pic_' . $lid . '_');
            //now let s upload the file
            if (!$uploader->upload()) {
                // if there are errors lets return them
                echo '<div style="color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center;"><p>' . $uploader->getErrors() . '</p></div>';

                return false;
            } else {
                // now let s create a new object picture and set its variables
                $picture = $this->create();
                $url     = $uploader->getSavedFileName();
                $picture->setVar('url', $url);
                $picture->setVar('title', $title);
                $uid = $xoopsUser->getVar('uid');
                $lid = $lid;
                $picture->setVar('lid', $lid);
                $picture->setVar('uid_owner', $uid);
                $this->insert($picture);
                $saved_destination = $uploader->getSavedDestination();
                $this->resizeImage($saved_destination, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload);
                //            function resizeImage($img, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload)
                //            Parameter '$saved_destination' type is not compatible with declaration
            }
        } else {
            echo '<div style="color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center;"><p>' . $uploader->getErrors() . '</p></div>';

            return false;
        }

        return true;
    }

    /**
     * Resize a picture and save it to $path_upload
     *
     * @param text $img         the path to the file
     * @param int  $thumbwidth  the width in pixels that the thumbnail will have
     * @param int  $thumbheight the height in pixels that the thumbnail will have
     * @param int  $pictwidth   the width in pixels that the pic will have
     * @param int  $pictheight  the height in pixels that the pic will have
     *
     * @param text $path_upload The path to where the files should be saved after resizing
     * @return void
     */
    public function resizeImage($img, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload)
    {
        $img2   = $img;
        $path   = pathinfo($img);
        $img    = imagecreatefromjpeg($img);
        $xratio = $thumbwidth / imagesx($img);
        $yratio = $thumbheight / imagesy($img);
        if ($xratio < 1 || $yratio < 1) {
            if ($xratio < $yratio) {
                $resized = imagecreatetruecolor($thumbwidth, floor(imagesy($img) * $xratio));
            } else {
                $resized = imagecreatetruecolor(floor(imagesx($img) * $yratio), $thumbheight);
            }
            imagecopyresampled($resized, $img, 0, 0, 0, 0, imagesx($resized) + 1, imagesy($resized) + 1, imagesx($img), imagesy($img));
            imagejpeg($resized, $path_upload . '/thumbs/thumb_' . $path['basename']);
            imagedestroy($resized);
        } else {
            imagejpeg($img, $path_upload . '/thumbs/thumb_' . $path['basename']);
        }
        imagedestroy($img);
        $path2   = pathinfo($img2);
        $img2    = imagecreatefromjpeg($img2);
        $xratio2 = $pictwidth / imagesx($img2);
        $yratio2 = $pictheight / imagesy($img2);
        if ($xratio2 < 1 || $yratio2 < 1) {
            if ($xratio2 < $yratio2) {
                $resized2 = imagecreatetruecolor($pictwidth, floor(imagesy($img2) * $xratio2));
            } else {
                $resized2 = imagecreatetruecolor(floor(imagesx($img2) * $yratio2), $pictheight);
            }
            imagecopyresampled($resized2, $img2, 0, 0, 0, 0, imagesx($resized2) + 1, imagesy($resized2) + 1, imagesx($img2), imagesy($img2));
            imagejpeg($resized2, $path_upload . '/midsize/resized_' . $path2['basename']);
            imagedestroy($resized2);
        } else {
            imagejpeg($img2, $path_upload . '/midsize/resized_' . $path2['basename']);
        }
        imagedestroy($img2);
    }
}
