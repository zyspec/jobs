CREATE TABLE jobs_listing (
  lid          INT(11)      NOT NULL AUTO_INCREMENT,
  cid          INT(11)      NOT NULL DEFAULT '0',
  title        VARCHAR(100) NOT NULL DEFAULT '',
  status       INT(3)       NOT NULL DEFAULT '1',
  expire       CHAR(3)      NOT NULL DEFAULT '',
  type         VARCHAR(100) NOT NULL DEFAULT '',
  company      VARCHAR(100) NOT NULL DEFAULT '',
  desctext     TEXT         NOT NULL,
  requirements TEXT         NOT NULL,
  tel          VARCHAR(30)  NOT NULL DEFAULT '',
  price        VARCHAR(100) NOT NULL DEFAULT '',
  typeprice    VARCHAR(100) NOT NULL DEFAULT '',
  contactinfo  MEDIUMTEXT   NOT NULL,
  contactinfo1 MEDIUMTEXT   NOT NULL,
  contactinfo2 MEDIUMTEXT   NOT NULL,
  date         INT(10)      NOT NULL DEFAULT '0',
  email        VARCHAR(100) NOT NULL DEFAULT '',
  submitter    VARCHAR(60)  NOT NULL DEFAULT '',
  usid         VARCHAR(6)   NOT NULL DEFAULT '',
  town         VARCHAR(100) NOT NULL DEFAULT '',
  state        VARCHAR(100) NOT NULL DEFAULT '',
  valid        INT(3)       NOT NULL DEFAULT '1',
  premium      TINYINT(2)   NOT NULL DEFAULT '0',
  photo        VARCHAR(100) NOT NULL DEFAULT '',
  view         VARCHAR(10)  NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
)
  ENGINE = MyISAM;

INSERT INTO jobs_listing VALUES
  (2, 1, 'Example Job', '1', '14', 'Full Time', 'Example Company', 'Here you can put a complete description of the job you are offering.', 'Here you can put all the requirements you have for the Job being offered.', '', '16.00', 'Per Hour',
                                                                                                                                                                                                                                     'Some Examples would be:\r\n\r\n1. Send Resume to:\r\n   Example Company\r\n   22 Example Adrress\r\n   Southington, Ct. 06489\r\n\r\n2. Reply in person',
                                                                                                                                                                                                                                     '', '', '1083798448',
                                                                                                                                                                                                                                     'admin@jlmzone.com',
                                                                                                                                                                                                                                     'john', '1',
                                                                                                                                                                                                                                     'Southington', 'Ct',
   '1', '0', '', '0');
# --------------------------------------------------------

CREATE TABLE jobs_resume (
  lid       INT(11)      NOT NULL AUTO_INCREMENT,
  cid       INT(11)      NOT NULL DEFAULT '0',
  name      VARCHAR(100) NOT NULL DEFAULT '',
  title     VARCHAR(100) NOT NULL DEFAULT '',
  status    INT(3)       NOT NULL DEFAULT '1',
  exp       VARCHAR(100) NOT NULL DEFAULT '',
  expire    CHAR(3)      NOT NULL DEFAULT '',
  private   VARCHAR(100) NOT NULL DEFAULT '',
  tel       VARCHAR(30)  NOT NULL DEFAULT '',
  salary    VARCHAR(100) NOT NULL DEFAULT '',
  typeprice VARCHAR(100) NOT NULL DEFAULT '',
  date      INT(10)      NOT NULL DEFAULT '0',
  email     VARCHAR(100) NOT NULL DEFAULT '',
  submitter VARCHAR(60)  NOT NULL DEFAULT '',
  usid      VARCHAR(6)   NOT NULL DEFAULT '',
  town      VARCHAR(100) NOT NULL DEFAULT '',
  state     VARCHAR(100) NOT NULL DEFAULT '',
  valid     INT(3)       NOT NULL DEFAULT '1',
  rphoto    VARCHAR(100) NOT NULL DEFAULT '',
  resume    VARCHAR(100) NOT NULL DEFAULT '',
  view      VARCHAR(10)  NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
)
  ENGINE = MyISAM;

CREATE TABLE jobs_categories (
  cid      INT(11)         NOT NULL AUTO_INCREMENT,
  pid      INT(5) UNSIGNED NOT NULL DEFAULT '0',
  title    VARCHAR(100)    NOT NULL DEFAULT '',
  img      VARCHAR(150)    NOT NULL DEFAULT '',
  ordre    INT(5)          NOT NULL DEFAULT '0',
  affprice INT(5)          NOT NULL DEFAULT '0',
  PRIMARY KEY (cid)
)
  ENGINE = MyISAM;

INSERT INTO jobs_categories VALUES (1, 0, 'Job Listings', 'default.gif', 0, 1);

CREATE TABLE jobs_res_categories (
  cid      INT(11)         NOT NULL AUTO_INCREMENT,
  pid      INT(5) UNSIGNED NOT NULL DEFAULT '0',
  title    VARCHAR(100)    NOT NULL DEFAULT '',
  img      VARCHAR(150)    NOT NULL DEFAULT '',
  ordre    INT(5)          NOT NULL DEFAULT '0',
  affprice INT(5)          NOT NULL DEFAULT '0',
  PRIMARY KEY (cid)
)
  ENGINE = MyISAM;

INSERT INTO jobs_res_categories VALUES (1, 0, 'Medical', 'default.gif', 0, 1);

CREATE TABLE jobs_type (
  id_type  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_type VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type)
)
  ENGINE = MyISAM;

INSERT INTO jobs_type VALUES (1, 'Full Time');
INSERT INTO jobs_type VALUES (2, 'Part Time');

CREATE TABLE jobs_price (
  id_price  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_price VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_price)
)
  ENGINE = MyISAM;

INSERT INTO jobs_price VALUES (1, 'Per Hour');
INSERT INTO jobs_price VALUES (2, 'Annual');

CREATE TABLE jobs_companies (
  comp_id            INT(11)         NOT NULL AUTO_INCREMENT,
  comp_pid           INT(5) UNSIGNED NOT NULL DEFAULT '0',
  comp_name          VARCHAR(100)    NOT NULL DEFAULT '',
  comp_address       VARCHAR(100)    NOT NULL DEFAULT '',
  comp_address2      VARCHAR(100)    NOT NULL DEFAULT '',
  comp_city          VARCHAR(100)    NOT NULL DEFAULT '',
  comp_state         VARCHAR(100)    NOT NULL DEFAULT '',
  comp_zip           VARCHAR(20)     NOT NULL DEFAULT '',
  comp_phone         VARCHAR(30)     NOT NULL DEFAULT '',
  comp_fax           VARCHAR(30)     NOT NULL DEFAULT '',
  comp_url           VARCHAR(150)    NOT NULL DEFAULT '',
  comp_img           VARCHAR(150)    NOT NULL DEFAULT '',
  comp_usid          VARCHAR(6)      NOT NULL DEFAULT '',
  comp_user1         VARCHAR(6)      NOT NULL DEFAULT '',
  comp_user2         VARCHAR(6)      NOT NULL DEFAULT '',
  comp_contact       TEXT            NOT NULL,
  comp_user1_contact TEXT            NOT NULL,
  comp_user2_contact TEXT            NOT NULL,
  comp_date_added    INT(10)         NOT NULL DEFAULT '0',
  PRIMARY KEY (comp_id),
  KEY comp_name (comp_name)
)
  ENGINE = MyISAM;

CREATE TABLE jobs_replies (
  r_lid     INT(11)      NOT NULL AUTO_INCREMENT,
  lid       INT(11)      NOT NULL DEFAULT '0',
  title     VARCHAR(100) NOT NULL DEFAULT '',
  date      INT(10)      NOT NULL DEFAULT '0',
  submitter VARCHAR(60)  NOT NULL DEFAULT '',
  message   TEXT         NOT NULL,
  resume    VARCHAR(100) NOT NULL DEFAULT '',
  tele      VARCHAR(30)  NOT NULL DEFAULT '',
  email     VARCHAR(100) NOT NULL DEFAULT '',
  r_usid    INT(11)      NOT NULL DEFAULT '0',
  company   VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (r_lid),
  KEY lid (lid)
)
  ENGINE = MyISAM;

CREATE TABLE jobs_created_resumes (
  res_lid     INT(11) NOT NULL AUTO_INCREMENT,
  lid         INT(11) NOT NULL DEFAULT '0',
  made_resume TEXT    NOT NULL,
  date        INT(10) NOT NULL DEFAULT '0',
  usid        INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (res_lid),
  KEY lid (lid)
)
  ENGINE = MyISAM;

CREATE TABLE jobs_pictures (
  cod_img       INT(11)      NOT NULL AUTO_INCREMENT,
  title         VARCHAR(255) NOT NULL,
  date_added    INT(10)      NOT NULL DEFAULT '0',
  date_modified INT(10)      NOT NULL DEFAULT '0',
  lid           INT(11)      NOT NULL DEFAULT '0',
  uid_owner     VARCHAR(50)  NOT NULL,
  url           TEXT         NOT NULL,
  PRIMARY KEY (cod_img)
)
  ENGINE = MyISAM;


CREATE TABLE jobs_region (
  rid    INT(11)         NOT NULL AUTO_INCREMENT,
  pid    INT(5) UNSIGNED NOT NULL DEFAULT '0',
  name   CHAR(40)        NOT NULL,
  abbrev CHAR(2)         NOT NULL,
  PRIMARY KEY (rid)
)
  ENGINE = MyISAM;








