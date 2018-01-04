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
xoops_cp_header();
$moduleDirName = $xoopsModule->getVar('dirname');
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/functions.php';

if (!@require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/main.php') {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/main.php';
}

$myts = \MyTextSanitizer::getInstance();

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;
    // 1) Create the resume table IF it does not exist
    if (!JobTableExists($xoopsDB->prefix('jobs_resume'))) {
        $sql1 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_resume') . " (
     lid INT(11) NOT NULL AUTO_INCREMENT,
     cid INT(11) NOT NULL DEFAULT '0',
     name VARCHAR(100) NOT NULL DEFAULT '',
     title VARCHAR(100) NOT NULL DEFAULT '',
     status INT(3) NOT NULL DEFAULT '1',
     exp VARCHAR(100) NOT NULL DEFAULT '',
     expire CHAR(3) NOT NULL DEFAULT '',
     private VARCHAR(6) NOT NULL DEFAULT '',
     tel VARCHAR(20) NOT NULL DEFAULT '',
     salary VARCHAR(100) NOT NULL DEFAULT '',
     typeprice VARCHAR(100) NOT NULL DEFAULT '',
     date INT(10)NOT NULL DEFAULT '0',
     email VARCHAR(100) NOT NULL DEFAULT '',
     submitter VARCHAR(60) NOT NULL DEFAULT '',
     usid VARCHAR(6) NOT NULL DEFAULT '',
     town VARCHAR(100) NOT NULL DEFAULT '',
     state VARCHAR(100) NOT NULL DEFAULT '',
     valid VARCHAR(11) NOT NULL DEFAULT '',
     resume VARCHAR(100) NOT NULL DEFAULT '',
     view VARCHAR(10) NOT NULL DEFAULT '0',
     PRIMARY KEY  (lid)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql1)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 2) Create the jobs_res_categories table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_res_categories'))) {
        $sql2 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_res_categories') . " (
      cid INT(11) NOT NULL AUTO_INCREMENT,
      pid INT(5) UNSIGNED NOT NULL DEFAULT '0',
      title VARCHAR(50) NOT NULL DEFAULT '',
      img VARCHAR(150) NOT NULL DEFAULT '',
      ordre INT(5) NOT NULL DEFAULT '0',
      affprice INT(5) NOT NULL DEFAULT '0',
      PRIMARY KEY  (cid)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql2)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 3) Create the jobs_replies table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_replies'))) {
        $sql3 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_replies') . " (
      r_lid INT(11) NOT NULL AUTO_INCREMENT,
      lid INT(5) UNSIGNED NOT NULL DEFAULT '0',
      title VARCHAR(50) NOT NULL DEFAULT '',
      date INT(10) NOT NULL DEFAULT '0',
      submitter VARCHAR(60) NOT NULL DEFAULT '',
      message TEXT NULL,
      resume VARCHAR(60) NOT NULL DEFAULT '',
      tele VARCHAR(20) NOT NULL DEFAULT '0',
      email VARCHAR(100) NOT NULL DEFAULT '',
      r_usid INT(11) NOT NULL DEFAULT '0',
      company VARCHAR(100) NOT NULL DEFAULT '',
      PRIMARY KEY  (r_lid)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql3)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 4) Create the jobs_companies table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_companies'))) {
        $sql4 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_companies') . " (
    comp_id INT(11) NOT NULL AUTO_INCREMENT,
    comp_pid INT(5) UNSIGNED NOT NULL DEFAULT '0',
    comp_name VARCHAR(50) NOT NULL DEFAULT '',
    comp_address VARCHAR(100) NOT NULL DEFAULT '',
    comp_address2 VARCHAR(100) NOT NULL DEFAULT '',
    comp_city VARCHAR(100) NOT NULL DEFAULT '',
    comp_state VARCHAR(100) NOT NULL DEFAULT '',
    comp_zip VARCHAR(20) NOT NULL DEFAULT '',
    comp_phone VARCHAR(50) NOT NULL DEFAULT '0',
    comp_fax VARCHAR(50) NOT NULL DEFAULT '',
    comp_url VARCHAR(150) NOT NULL DEFAULT '',
    comp_img VARCHAR(100) NOT NULL DEFAULT '',
    comp_usid VARCHAR(6) NOT NULL DEFAULT '',
    comp_user1 VARCHAR(6) NOT NULL DEFAULT '',
    comp_user2 VARCHAR(6) NOT NULL DEFAULT '',
    comp_contact TEXT NOT NULL,
    comp_user1_contact TEXT NOT NULL,
    comp_user2_contact TEXT NOT NULL,
    comp_date_added INT(10) NOT NULL DEFAULT '0',
      PRIMARY KEY  (comp_id),
      KEY comp_name (comp_name)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql4)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 5) Create the jobs_created_resumes table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_created_resumes'))) {
        $sql5 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_created_resumes') . " (
    res_lid INT(11) NOT NULL AUTO_INCREMENT,
    lid INT(11) NOT NULL DEFAULT '0',
    made_resume TEXT NOT NULL,
    date INT(10) NOT NULL DEFAULT '0',
    usid INT(11) NOT NULL DEFAULT '0',
      PRIMARY KEY  (res_lid),
          KEY lid (lid)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql5)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 6) Create the jobs_pictures table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_pictures'))) {
        $sql6 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_pictures') . " (

    cod_img INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    date_added INT(10) NOT NULL DEFAULT '0',
    date_modified INT(10) NOT NULL DEFAULT '0',
    lid INT(11) NOT NULL DEFAULT '0',
    uid_owner VARCHAR(50) NOT NULL,
    url TEXT NOT NULL,
      PRIMARY KEY  (cod_img)
    ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql6)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 6) Create the jobs_region table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_region'))) {
        $sql7 = 'CREATE TABLE ' . $xoopsDB->prefix('jobs_region') . " (

      rid INT(11) NOT NULL AUTO_INCREMENT,
      pid INT(5) UNSIGNED NOT NULL DEFAULT '0',
      name CHAR(50) NOT NULL,
      abbrev CHAR(2) NOT NULL,
    PRIMARY KEY  (rid)
      ) ENGINE=MyISAM;";

        if (!$xoopsDB->queryF($sql7)) {
            echo '<br>' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 7) Add the status field to the jobs_listing table
    if (!JobFieldExists('status', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("status INT(3) NOT null default '1' AFTER `title`", $xoopsDB->prefix('jobs_listing'));
    }

    // 8) Add the expire field to the jobs_listing table
    if (!JobFieldExists('expire', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("expire VARCHAR(6) NOT null default '14' AFTER `title`", $xoopsDB->prefix('jobs_listing'));
    }

    // 8) Add the expire field to the jobs_listing table
    if (!JobFieldExists('contactinfo1', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField('contactinfo1 MEDIUMTEXT NOT null AFTER `contactinfo`', $xoopsDB->prefix('jobs_listing'));
    }

    // 8) Add the expire field to the jobs_listing table
    if (!JobFieldExists('contactinfo2', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField('contactinfo2 MEDIUMTEXT NOT null AFTER `contactinfo1`', $xoopsDB->prefix('jobs_listing'));
    }

    // 9) Add the state field to the jobs_listing table
    if (!JobFieldExists('state', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("state VARCHAR(100) NOT null default '' AFTER `town`", $xoopsDB->prefix('jobs_listing'));
    }

    // 10) Add the premium field to the jobs_listing table
    if (!JobFieldExists('premium', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("premium TINYINT(2) NOT null default '0' AFTER `valid`", $xoopsDB->prefix('jobs_listing'));
    }

    // 11) Add the comp_date_added field to the jobs_companies table
    if (!JobFieldExists('comp_pid', $xoopsDB->prefix('jobs_companies'))) {
        JobAddField("comp_pid INT(5) unsigned NOT null default '0' AFTER `comp_id`", $xoopsDB->prefix('jobs_companies'));
    }

    // 11) Add the comp_date_added field to the jobs_companies table
    if (!JobFieldExists('comp_date_added', $xoopsDB->prefix('jobs_companies'))) {
        JobAddField("comp_date_added INT(10) NOT null default '0' AFTER `comp_user2_contact`", $xoopsDB->prefix('jobs_companies'));
    }
    // 12) Add the status field to the jobs_resume table
    if (!JobFieldExists('status', $xoopsDB->prefix('jobs_resume'))) {
        JobAddField("status INT(3) NOT null default '0' AFTER `title`", $xoopsDB->prefix('jobs_resume'));
    }
    // 13) Add the state field to the jobs_resume table
    if (!JobFieldExists('state', $xoopsDB->prefix('jobs_resume'))) {
        JobAddField("state VARCHAR(100) NOT null default '' AFTER `town`", $xoopsDB->prefix('jobs_resume'));
    }
    // 14) Add the company field to the jobs_replies table
    if (!JobFieldExists('company', $xoopsDB->prefix('jobs_replies'))) {
        JobAddField("company VARCHAR(100) NOT null default '' AFTER `r_usid`", $xoopsDB->prefix('jobs_replies'));
    }
    // At the end, if there were errors, show them or redirect user to the module's upgrade page
    if ($errors) {
        echo '<H1>' . _AM_JOBS_UPGRADEFAILED . '</H1>';
        echo '<br>' . _AM_JOBS_UPGRADEFAILED0;
    } else {
        echo '' . _AM_JOBS_UPDATECOMPLETE . " - <a href='" . XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $moduleDirName . "'>" . _AM_JOBS_UPDATEMODULE . '</a>';
    }
} else {
    printf("<H2>%s</H2>\n", _AM_JOBS_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
