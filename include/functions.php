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
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller                                    //
// Author Website : pascal.e-xoops@perso-search.com                          //
// Licence Type   : GPL                                                      //
// ------------------------------------------------------------------------- //

$moduleDirName = basename(dirname(__DIR__));

//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/gtickets.php";

function ExpireJob()
{
    global $xoopsDB, $xoopsConfig, $xoopsModuleConfig, $myts, $meta, $moduleDirName;

    $datenow = time();

    $result5 = $xoopsDB->query('SELECT lid, title, expire, type, company, desctext, requirements, contactinfo, date, email, submitter, usid, photo, view FROM ' . $xoopsDB->prefix('jobs_listing') . " WHERE valid='1'");

    while (list($lids, $title, $expire, $type, $company, $desctext, $requirements, $contactinfo, $dateann, $email, $submitter, $usid, $photo, $lu) = $xoopsDB->fetchRow($result5)) {
        $title        = $myts->addSlashes($title);
        $expire       = $myts->addSlashes($expire);
        $type         = $myts->addSlashes($type);
        $company      = $myts->addSlashes($company);
        $desctext     = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
        $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
        $contactinfo  = $myts->addSlashes($contactinfo);
        $submitter    = $myts->addSlashes($submitter);
        $usid         = (int)$usid;

        $supprdate = $dateann + ($expire * 86400);
        if ($supprdate < $datenow) {
            $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE lid=' . $xoopsDB->escape($lids) . '');

            $destination = XOOPS_ROOT_PATH . "/modules/$moduleDirName/logo_images";

            if ($photo) {
                if (file_exists("$destination/$photo")) {
                    unlink("$destination/$photo");
                }
            }

            $comp_id     = jobs_getCompIdFromName($company);
            $extra_users = jobs_getCompany($comp_id, $usid);

            $extra_user1 = $extra_users['comp_user1'];
            $extra_user2 = $extra_users['comp_user2'];

            if ($extra_user1) {
                $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uid=$extra_user1");
                list($extra_user1_email) = $xoopsDB->fetchRow($result);
                $extra_user1_email = $extra_user1_email;
            } else {
                $extra_user1_email = '';
            }

            if ($extra_user2) {
                $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uid=$extra_user2");
                list($extra_user2_email) = $xoopsDB->fetchRow($result);
                $extra_user2_email = $extra_user2_email;
            } else {
                $extra_user2_email = '';
            }

            if ($email) {
                $tags                = [];
                $tags['TITLE']       = $title;
                $tags['TYPE']        = $type;
                $tags['COMPANY']     = $company;
                $tags['DESCTEXT']    = $desctext;
                $tags['MY_SITENAME'] = $xoopsConfig['sitename'];
                $tags['REPLY_ON']    = _JOBS_REMINDANN;
                $tags['DESCRIPT']    = _JOBS_DESC;
                $tags['TO']          = _JOBS_TO;
                $tags['SUBMITTER']   = $submitter;
                $tags['EMAIL']       = _JOBS_EMAIL;
                $tags['HELLO']       = _JOBS_HELLO;
                $tags['YOUR_JOB']    = _JOBS_YOUR_JOB;
                $tags['THANKS']      = _JOBS_THANK;
                $tags['WEBMASTER']   = _JOBS_WEBMASTER;
                $tags['AT']          = _JOBS_AT;
                $tags['SENDER_IP']   = $_SERVER['REMOTE_ADDR'];
                $tags['HITS']        = $lu;
                $tags['TIMES']       = _JOBS_TIMES;
                $tags['VIEWED']      = _JOBS_VIEWED;
                $tags['ON']          = _JOBS_ON;
                $tags['EXPIRED']     = _JOBS_EXPIRED;

                $subject = '' . _JOBS_STOP2 . '' . _JOBS_STOP3 . '';
                $mail    = xoops_getMailer();

                if (is_dir('language/' . $xoopsConfig['language'] . '/mail_template/')) {
                    $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $xoopsConfig['language'] . '/mail_template/');
                } else {
                    $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/english/mail_template/");
                }

                $mail->setTemplate('jobs_listing_expired.tpl');
                $mail->useMail();
                $mail->setFromEmail($xoopsConfig['adminmail']);
                $mail->setToEmails([$email, $extra_user1_email, $extra_user2_email]);
                $mail->setSubject($subject);
                $mail->multimailer->isHTML(true);
                $mail->assign($tags);
                $mail->send();
                echo $mail->getErrors();
            }
        }
    }
}

/**
 * @param        $sel_id
 * @param string $status
 *
 * @return int
 */
function jobs_getTotalItems($sel_id, $status = '')
{
    global $xoopsDB, $mytree, $moduleDirName;
    $categories = jobs_MygetItemIds('' . $moduleDirName . '_view');
    $count      = 0;
    $arr        = [];
    if (in_array($sel_id, $categories)) {
        $query = 'SELECT count(*) FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_listing') . ' WHERE cid=' . (int)$sel_id . " AND valid='1' AND status!='0'";

        $result = $xoopsDB->query($query);
        list($thing) = $xoopsDB->fetchRow($result);
        $count = $thing;
        $arr   = $mytree->getAllChildId($sel_id);
        $size  = count($arr);
        for ($i = 0; $i < $size; ++$i) {
            if (in_array($arr[$i], $categories)) {
                $query2 = 'SELECT count(*) FROM ' . $xoopsDB->prefix('' . $moduleDirName . '_listing') . ' WHERE cid=' . (int)$arr[$i] . " AND valid='1' AND status!='0'";

                $result2 = $xoopsDB->query($query2);
                list($thing) = $xoopsDB->fetchRow($result2);
                $count += $thing;
            }
        }
    }

    return $count;
}

function JobsShowImg()
{
    global $moduleDirName;

    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n\n";
    echo "function showimage() {\n";
    echo "if (!document.images)\n";
    echo "return\n";
    echo "document.images.avatar.src=\n";
    echo "'" . XOOPS_URL . "/modules/$moduleDirName/assets/images/cat/' + document.imcat.img.options[document.imcat.img.selectedIndex].value\n";
    echo "}\n\n";
    echo "//-->\n";
    echo "</script>\n";
}

//Reusable Link Sorting Functions
/**
 * @param $orderby
 *
 * @return string
 */
function jobs_convertorderbyin($orderby)
{
    switch (trim($orderby)) {
        case 'titleA':
            $orderby = 'title ASC';
            break;
        case 'dateA':
            $orderby = 'date ASC';
            break;
        case 'viewA':
            $orderby = 'view ASC';
            break;
        case 'companyA':
            $orderby = 'company ASC';
            break;
        case 'townA':
            $orderby = 'town ASC';
            break;
        case 'stateA':
            $orderby = 'state ASC';
            break;
        case 'titleD':
            $orderby = 'title DESC';
            break;
        case 'viewD':
            $orderby = 'view DESC';
            break;
        case 'companyD':
            $orderby = 'company DESC';
            break;
        case 'townD':
            $orderby = 'town DESC';
            break;
        case 'stateD':
            $orderby = 'state DESC';
            break;
        case 'dateD':
        default:
            $orderby = 'date DESC';
            break;
    }

    return $orderby;
}

/**
 * @param $orderby
 *
 * @return string
 */
function jobs_convertorderbytrans($orderby)
{
    if ($orderby === 'view ASC') {
        $orderbyTrans = '' . _JOBS_POPULARITYLTOM . '';
    }
    if ($orderby === 'view DESC') {
        $orderbyTrans = '' . _JOBS_POPULARITYMTOL . '';
    }
    if ($orderby === 'title ASC') {
        $orderbyTrans = '' . _JOBS_TITLEATOZ . '';
    }
    if ($orderby === 'title DESC') {
        $orderbyTrans = '' . _JOBS_TITLEZTOA . '';
    }
    if ($orderby === 'date ASC') {
        $orderbyTrans = '' . _JOBS_DATEOLD . '';
    }
    if ($orderby === 'date DESC') {
        $orderbyTrans = '' . _JOBS_DATENEW . '';
    }
    if ($orderby === 'company ASC') {
        $orderbyTrans = '' . _JOBS_COMPANYATOZ . '';
    }
    if ($orderby === 'company DESC') {
        $orderbyTrans = '' . _JOBS_COMPANYZTOA . '';
    }
    if ($orderby === 'town ASC') {
        $orderbyTrans = '' . _JOBS_LOCALATOZ . '';
    }
    if ($orderby === 'town DESC') {
        $orderbyTrans = '' . _JOBS_LOCALZTOA . '';
    }
    if ($orderby === 'state ASC') {
        $orderbyTrans = '' . _JOBS_STATEATOZ . '';
    }
    if ($orderby === 'state DESC') {
        $orderbyTrans = '' . _JOBS_STATEZTOA . '';
    }

    return $orderbyTrans;
}

/**
 * @param $orderby
 *
 * @return string
 */
function jobs_convertorderby($orderby)
{
    if ($orderby === 'title ASC') {
        $orderby = 'titleA';
    }
    if ($orderby === 'date ASC') {
        $orderby = 'dateA';
    }
    if ($orderby === 'company ASC') {
        $orderby = 'companyA';
    }
    if ($orderby === 'town ASC') {
        $orderby = 'townA';
    }
    if ($orderby === 'state ASC') {
        $orderby = 'stateA';
    }
    if ($orderby === 'view ASC') {
        $orderby = 'viewA';
    }
    if ($orderby === 'title DESC') {
        $orderby = 'titleD';
    }
    if ($orderby === 'date DESC') {
        $orderby = 'dateD';
    }
    if ($orderby === 'company DESC') {
        $orderby = 'companyD';
    }
    if ($orderby === 'town DESC') {
        $orderby = 'townD';
    }
    if ($orderby === 'state DESC') {
        $orderby = 'stateD';
    }
    if ($orderby === 'view DESC') {
        $orderby = 'viewD';
    }

    return $orderby;
}

/**
 * @param $tablename
 *
 * @return bool
 */
function JobTableExists($tablename)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * @param $fieldname
 * @param $table
 *
 * @return bool
 */
function JobFieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * @param $field
 * @param $table
 *
 * @return mixed
 */
function JobAddField($field, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field");

    return $result;
}

/**
 * @param        $caption
 * @param        $name
 * @param string $value
 * @param string $width
 * @param string $height
 * @param string $supplemental
 *
 * @return XoopsFormEditor
 */
function jobs_getEditor($caption, $name, $value = '', $width = '99%', $height = '200px', $supplemental = '')
{
    global $xoopsModuleConfig;

    if ($xoopsModuleConfig['jobs_form_options'] === 'dhtmltextarea') {
        $nohtml = '1';
    } else {
        $nohtml = '0';
    }

    $editor_configs           = [];
    $editor_configs['name']   = $name;
    $editor_configs['value']  = $value;
    $editor_configs['rows']   = 25;
    $editor_configs['cols']   = 70;
    $editor_configs['width']  = '95%';
    $editor_configs['height'] = '12%';
    $editor_configs['editor'] = strtolower($xoopsModuleConfig['jobs_form_options']);
    if (is_readable(XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php')) {
        require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php';
        $editor = new XoopsFormEditor($caption, $name, $editor_configs, $nohtml, $onfailure = 'textarea');

        return $editor;
    }
}

/**
 * @param $uname
 *
 * @return bool
 */
function jobs_getIdFromUname($uname)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $sql = 'SELECT uid FROM ' . $xoopsDB->prefix('users') . " WHERE uname = '$uname'";

    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return false;
    }

    $uid = $arr['uid'];

    return $uid;
}

/**
 * @param $usid
 *
 * @return int
 */
function jobs_getCompCount($usid)
{
    global $xoopsDB, $xoopsUser;

    $sql    = 'SELECT count(*) AS count FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE ' . $usid . ' IN (comp_usid, comp_user1, comp_user2)';
    $result = $xoopsDB->query($sql);
    if (!$result) {
        return 0;
    } else {
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }
}

/**
 * @param int $usid
 *
 * @return array|int
 */
function jobs_getCompany($usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM '
           . $xoopsDB->prefix('jobs_companies')
           . ' WHERE '
           . $usid
           . ' IN (comp_usid, comp_user1, comp_user2)';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $company = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $company = $row;
    }

    return $company;
}

/**
 * @return array|int
 */
function jobs_getPriceType()
{
    global $xoopsDB;
    $sql = 'SELECT nom_type FROM ' . $xoopsDB->prefix('jobs_price') . ' ORDER BY nom_type';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    } else {
        $rows = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $rows[] = $row;
        }

        return $rows;
    }
}

/**
 * @param $permtype
 *
 * @return mixed
 */
function jobs_MygetItemIds($permtype)
{
    global $xoopsUser, $moduleDirName;
    static $permissions = [];
    if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
        return $permissions[$permtype];
    }

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler          = xoops_getHandler('module');
    $myModule               = $moduleHandler->getByDirname('jobs');
    $groups                 = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler           = xoops_getHandler('groupperm');
    $categories             = $gpermHandler->getItemIds($permtype, $groups, $myModule->getVar('mid'));
    $permissions[$permtype] = $categories;

    return $categories;
}

/**
 * @param $cid
 *
 * @return bool
 */
function jobs_getCatNameFromId($cid)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser, $moduleDirName;

    $sql = 'SELECT title FROM ' . $xoopsDB->prefix('jobs_categories') . " WHERE cid = '$cid'";

    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return false;
    }

    $title = $arr['title'];

    return $title;
}

/**
 * @param $rid
 *
 * @return bool
 */
function jobs_getStateNameFromId($rid)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser, $moduleDirName;

    $sql = 'SELECT name FROM ' . $xoopsDB->prefix('jobs_region') . " WHERE rid = '$rid'";

    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return false;
    }

    $name = $arr['name'];

    return $name;
}

/**
 * @param $name
 *
 * @return bool
 */
function jobs_getCompIdFromName($name)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $sql = 'SELECT comp_id FROM ' . $xoopsDB->prefix('jobs_companies') . " WHERE comp_name = '$name'";

    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }
    if (!$arr = $xoopsDB->fetchArray($result)) {
        return false;
    }

    $comp_id = $arr['comp_id'];

    return $comp_id;
}

/**
 * @param $usid
 *
 * @return array|int
 */
function jobs_getCompanyWithListing($usid)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM '
           . $xoopsDB->prefix('jobs_companies')
           . " WHERE comp_usid = '$usid' OR comp_user1 = '$usid' OR  comp_user2 = '$usid' order by comp_id";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $companies = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $companies = $row;
    }

    return $companies;
}

/**
 * @param $cid
 *
 * @return array|int
 */
function jobs_getPremiumListings($cid)
{
    global $xoopsDB, $xoopsUser;

    $sql = 'SELECT lid, cid, title, status, expire, type, company, price, typeprice, date, town, state, valid, premium, photo, view FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE cid=' . $xoopsDB->escape($cid) . " AND valid='1' AND premium='1' AND status!='0' ORDER BY date";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $premium_listings = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $premium_listings = $row;
    }

    return $premium_listings;
}

/**
 * @return array|int
 */
function jobs_getAllCompanies()
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM ' . $xoopsDB->prefix('jobs_companies') . '';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $companies = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $companies = $row;
    }

    return $companies;
}

/**
 * @param $cat
 *
 * @return string
 */
function jobs_categorynewgraphic($cat)
{
    global $xoopsDB, $moduleDirName, $xoopsUser, $xoopsModuleConfig;

    $newresult = $xoopsDB->query('SELECT date FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE cid=' . $xoopsDB->escape($cat) . " AND valid = '1' ORDER BY date DESC LIMIT 1");
    list($date) = $xoopsDB->fetchRow($newresult);

    $useroffset = '';
    if ($xoopsUser) {
        $timezone = $xoopsUser->timezone();
        if (isset($timezone)) {
            $useroffset = $xoopsUser->timezone();
        } else {
            $useroffset = $xoopsConfig['default_TZ'];
        }
    }
    $date = ($useroffset * 3600) + $date;

    $days_new  = $xoopsModuleConfig['jobs_countday'];
    $startdate = (time() - (86400 * $days_new));

    if ($startdate < $date) {
        return "<img src=\"" . XOOPS_URL . "/modules/$moduleDirName/assets/images/newred.gif\">";
    }
}

/**
 * @param $cid
 *
 * @return bool
 */
function jobs_subcatnew($cid)
{
    global $xoopsDB, $moduleDirName;

    $newresult = $xoopsDB->query('SELECT date FROM ' . $xoopsDB->prefix('jobs_listing') . ' WHERE cid=' . $xoopsDB->escape($cid) . " AND valid = '1' ORDER BY date DESC LIMIT 1");
    list($timeann) = $xoopsDB->fetchRow($newresult);

    $count     = 1;
    $startdate = (time() - (86400 * $count));

    if ($startdate < $timeann) {
        return true;
    }
}

/**
 * @param $date
 *
 * @return string
 */
function jobs_listingnewgraphic($date)
{
    global $xoopsDB, $moduleDirName, $xoopsModuleConfig;

    $days_new  = $xoopsModuleConfig['jobs_countday'];
    $startdate = ((int)time() - (86400 * (int)$days_new));

    if ($startdate < $date) {
        return "<img src=\"" . XOOPS_URL . "/modules/$moduleDirName/assets/images/newred.gif\">";
    }
}

/**
 * @param $comp_id
 *
 * @return bool
 */
function jobs_getCompNameFromId($comp_id)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $sql = 'SELECT comp_name FROM ' . $xoopsDB->prefix('jobs_companies') . " WHERE comp_id = '$comp_id'";

    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return false;
    }

    $comp_name = $arr['comp_name'];

    return $comp_name;
}

/**
 * @param int $comp_id
 * @param int $usid
 *
 * @return array|int
 */
function jobs_getCompanyUsers($comp_id = 0, $usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_usid, comp_user1, comp_user2 FROM ' . $xoopsDB->prefix('jobs_companies') . " WHERE comp_id = '$comp_id' AND  " . $usid . ' IN (comp_usid, comp_user1, comp_user2)';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $their_comp = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $their_comp = $row;
    }

    return $their_comp;
}

/**
 * @param int $comp_id
 * @param int $member_usid
 *
 * @return array|int
 */
function jobs_getXtraUsers($comp_id = 0, $member_usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_user1, comp_user2 FROM ' . $xoopsDB->prefix('jobs_companies') . " WHERE comp_id = '$comp_id' AND " . $member_usid . ' IN (comp_user1, comp_user2)';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $xtra_users = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $xtra_users = $row;
    }

    return $xtra_users;
}

/**
 * @param int $member_usid
 *
 * @return array|int
 */
function jobs_getAllUserCompanies($member_usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_usid, comp_user1, comp_user2 FROM ' . $xoopsDB->prefix('jobs_companies') . ' WHERE ' . $member_usid . ' IN (comp_usid, comp_user1, comp_user2)';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $xtra_users = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $xtra_users = $row;
    }

    return $xtra_users;
}

/**
 * @param     $comp_id
 * @param int $usid
 *
 * @return array|int
 */
function jobs_getThisCompany($comp_id, $usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM '
           . $xoopsDB->prefix('jobs_companies')
           . " WHERE comp_id = '$comp_id' AND "
           . $usid
           . ' IN ( comp_usid, comp_user1, comp_user2)';
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $thiscompany = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $thiscompany = $row;
    }

    return $thiscompany;
}

/**
 * @param int $comp_id
 *
 * @return array|int
 */
function jobs_getACompany($comp_id = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = 'SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM '
           . $xoopsDB->prefix('jobs_companies')
           . " WHERE comp_id='$comp_id'";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $company = [];
    while ($row = $xoopsDB->fetchArray($result)) {
        $company = $row;
    }

    return $company;
}

/**
 * @return bool
 */
function jobs_isX24plus()
{
    $x24plus = false;
    $xv      = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) >= '4') {
        $x24plus = true;
    }

    return $x24plus;
}

/**
 * Do some basic file checks and stuff.
 * Author: Andrew Mills  Email:  ajmills@sirium.net
 * from amReviews module
 */
function jobs_filechecks()
{
    global $xoopsModule, $xoopsConfig;

    echo '<fieldset>';
    echo "<legend style=\"color: #990000; font-weight: bold;\">" . _AM_JOBS_FILECHECKS . '</legend>';
    /*
        $photodir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo";
        $photothumbdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo/thumbs";
        $photohighdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo/midsize";
        $cachedir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/resumes";
        $tmpdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/rphoto";
        $logo_images_dir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/logo_images";

        if (file_exists($photodir)) {
            if (!is_writable($photodir)) {
                echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photodir . "<br>";
            } else {
                echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $photodir . "<br>";
            }
        } else {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $photodir . " does NOT exist!<br>";
        }
        // photothumbdir
        if (file_exists($photothumbdir)) {
            if (!is_writable($photothumbdir)) {
                echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photothumbdir . "<br>";
            } else {
                echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $photothumbdir . "<br>";
            }
        } else {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $photothumbdir . " does NOT exist!<br>";
        }
        // photohighdir
        if (file_exists($photohighdir)) {
            if (!is_writable($photohighdir)) {
                echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photohighdir . "<br>";
            } else {
                echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $photohighdir . "<br>";
            }
        } else {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $photohighdir . " does NOT exist!<br>";
        }
        // cachedir
        if (file_exists($cachedir)) {
            if (!is_writable($cachedir)) {
                echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $cachedir . "<br>";
            } else {
                echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $cachedir . "<br>";
            }
        } else {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $cachedir . " does NOT exist!<br>";
        }
        // tmpdir
        if (file_exists($tmpdir)) {
            if (!is_writable($tmpdir)) {
                echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $tmpdir . "<br>";
            } else {
                echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $tmpdir . "<br>";
            }
        } else {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $tmpdir . " does NOT exist!<br>";
        }
        if (file_exists($logo_images_dir)) {
            if (!is_writable($logo_images_dir)) {
                echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $logo_images_dir . "<br>";
            } else {
                echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $logo_images_dir . "<br>";
            }
        } else {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $logo_images_dir . " does NOT exist!<br>";
        }
    */

    /**
     * Some info.
     */
    $uploads = ini_get('file_uploads') ? _AM_JOBS_UPLOAD_ON : _AM_JOBS_UPLOAD_OFF;
    //  echo "<br>";
    echo '<ul>';
    echo '<li>' . _AM_JOBS_UPLOADMAX . '<b>' . ini_get('upload_max_filesize') . '</b></li>';
    echo '<li>' . _AM_JOBS_POSTMAX . '<b>' . ini_get('post_max_size') . '</b></li>';
    echo '<li>' . _AM_JOBS_UPLOADS . '<b>' . $uploads . '</b></li>';

    $gdinfo = gd_info();
    if (function_exists('gd_info')) {
        echo '<li>' . _AM_JOBS_GDIMGSPPRT . '<b>' . _AM_JOBS_GDIMGON . '</b></li>';
        echo '<li>' . _AM_JOBS_GDIMGVRSN . '<b>' . $gdinfo['GD Version'] . '</b></li>';
    } else {
        echo '<li>' . _AM_JOBS_GDIMGSPPRT . '<b>' . _AM_JOBS_GDIMGOFF . '</b></li>';
    }
    echo '</ul>';

    //$inithingy = ini_get_all();
    //print_r($inithingy);

    echo '</fieldset>';
} // end function

//----------------------------------------------------------------------------//

/**
 * @return array
 */
function jobs_summary()
{
    global $xoopsDB;

    //-----------------------
    /*
    * jobs
     * validate
     * published
     * total jobs
     * Categories

    * resumes
     * validate
     * published
     * total jobs
     * Categories

     * payment type
     * job type
     *
    * Companies
    *
    * */

    $summary = [];

    /**
     * As many of these will be "joined" at some point.
     */

    /**
     * Waiting JOB validation.
     */

    //    $result2  = $xoopsDB->query(
    //        "select lid, title, date from " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0' order by lid"
    //    );

    $result = $xoopsDB->query('SELECT COUNT(lid) AS waitJobValidation FROM ' . $xoopsDB->prefix('jobs_listing') . " WHERE valid='0'");
    list($waitJobValidation) = $xoopsDB->fetchRow($result);// {

    if ($waitJobValidation < 1) {
        $summary['waitJobValidation'] = "<span style=\"font-weight: bold;\">0</span>";
    } else {
        $summary['waitJobValidation'] = "<span style=\"font-weight: bold; color: red;\">" . $waitJobValidation . '</span>';
    }

    //       $result1  = $xoopsDB->query("select lid, title, date from " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0' order by lid");
    //       $numrows1 = $xoopsDB->getRowsNum($result1);
    //

    /**
     * Waiting RESUME validation.
     */

    //    $result2  = $xoopsDB->query(
    //        "select lid, title, date from " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0' order by lid"
    //    );

    $result = $xoopsDB->query('SELECT COUNT(lid) AS waitResumeValidation FROM ' . $xoopsDB->prefix('jobs_resume') . " WHERE valid='0'");
    list($waitResumeValidation) = $xoopsDB->fetchRow($result);// {

    if ($waitResumeValidation < 1) {
        $summary['waitResumeValidation'] = "<span style=\"font-weight: bold;\">0</span>";
    } else {
        $summary['waitResumeValidation'] = "<span style=\"font-weight: bold; color: red;\">" . $waitResumeValidation . '</span>';
    }

    //       $result1  = $xoopsDB->query("select lid, title, date from " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0' order by lid");
    //       $numrows1 = $xoopsDB->getRowsNum($result1);

    /**
     * Jobs Published count (total)
     */

    $result = $xoopsDB->query('SELECT COUNT(lid) AS jobPublished FROM ' . $xoopsDB->prefix('jobs_listing') . " WHERE valid='1'");
    list($jobPublished) = $xoopsDB->fetchRow($result);// {

    if (!$result) {
        $summary['jobPublished'] = 0;
    } else {
        $summary['jobPublished'] = "<span style=\"font-weight: bold; color: green;\">" . $jobPublished . '</span>';
    }

    /**
     * Job Category count (total)
     */
    $result = $xoopsDB->query('SELECT COUNT(cid) AS jobCategoryCount FROM ' . $xoopsDB->prefix('jobs_categories') . ' ');
    list($jobCategoryCount) = $xoopsDB->fetchRow($result);// {

    if (!$result) {
        $summary['jobCategoryCount'] = 0;
    } else {
        $summary['jobCategoryCount'] = "<span style=\"font-weight: bold; color: green;\">" . $jobCategoryCount . '</span>';
    }
    unset($result);

    /**
     * Resumes Published count (total)
     */

    $result = $xoopsDB->query('SELECT COUNT(lid) AS resumePublished FROM ' . $xoopsDB->prefix('jobs_resume') . " WHERE valid='1'");
    list($resumePublished) = $xoopsDB->fetchRow($result);// {

    if (!$result) {
        $summary['resumePublished'] = 0;
    } else {
        $summary['resumePublished'] = "<span style=\"font-weight: bold; color: green;\">" . $resumePublished . '</span>';
    }

    /**
     * Resume Category count (total)
     */
    $result = $xoopsDB->query('SELECT COUNT(cid) AS resumeCategoryCount FROM ' . $xoopsDB->prefix('jobs_res_categories') . ' ');
    list($resumeCategoryCount) = $xoopsDB->fetchRow($result);// {

    if (!$result) {
        $summary['resumeCategoryCount'] = 0;
    } else {
        $summary['resumeCategoryCount'] = "<span style=\"font-weight: bold; color: green;\">" . $resumeCategoryCount . '</span>';
    }
    unset($result);

    /**
     * Company count (total)
     */
    $result = $xoopsDB->query('SELECT COUNT(comp_id) AS companies FROM ' . $xoopsDB->prefix('jobs_companies') . ' ');
    list($companies) = $xoopsDB->fetchRow($result);// {

    if (!$result) {
        $summary['companies'] = 0;
    } else {
        $summary['companies'] = "<span style=\"font-weight: bold; color: green;\">" . $companies . '</span>';
    }
    unset($result);

    /**
     * Published (total)
     *
     * $result = $xoopsDB->query("SELECT count(id) AS published FROM " .$xoopsDB->prefix('amreview_reviews') . " WHERE showme='1' AND validated='1'");
     * list($published) = $xoopsDB->fetchRow($result);// {
     *
     * if (!$result) { $summary['published'] = 0; } else { $summary['published'] = $published; }
     * unset($result);
     * Hidden (total)
     *
     * $result = $xoopsDB->query("SELECT count(id) AS hidden FROM " .$xoopsDB->prefix('amreview_reviews') . " WHERE showme='0' OR validated='0'");
     * list($hidden) = $xoopsDB->fetchRow($result);// {
     *
     * if (!$result) { $summary['hidden'] = 0; } else { $summary['hidden'] = $hidden; }
     * unset($result);
     */

    //print_r($summary);
    return $summary;
} // end function

//----------------------------------------------------------------------------//

/**
 * @param        $global
 * @param        $key
 * @param string $default
 * @param string $type
 *
 * @return mixed|string
 */
function jobs_CleanVars(&$global, $key, $default = '', $type = 'int')
{
    switch ($type) {
        case 'string':
            $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
            break;
        case 'int':
        default:
            $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
            break;
    }
    if ($ret === false) {
        return $default;
    }

    return $ret;
}
