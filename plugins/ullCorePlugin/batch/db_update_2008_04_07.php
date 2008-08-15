<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('myApp', 'cli', true);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();
$connection = Propel::getConnection();

/* db update */

//$query = '';
//$result = mysql_query($query);
//if (!$result) {
//   echo "error: ", mysql_error();
//}

$query = 'CREATE TABLE `ull_select`
(
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `caption_i18n_default` VARCHAR(64),
  `creator_user_id` INTEGER,
  `created_at` DATETIME,
  `updator_user_id` INTEGER,
  `updated_at` DATETIME,
  PRIMARY KEY (`id`)
)Type=MyISAM';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_select` ADD `slug` VARCHAR(32) AFTER `id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


$query = 'CREATE TABLE `ull_select_i18n`
(
  `caption_i18n` VARCHAR(64),
  `id` INTEGER  NOT NULL,
  `culture` VARCHAR(7)  NOT NULL,
  PRIMARY KEY (`id`,`culture`),
  CONSTRAINT `ull_select_i18n_FK_1`
    FOREIGN KEY (`id`)
    REFERENCES `ull_select` (`id`)
    ON DELETE CASCADE
)Type=MyISAM';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'CREATE TABLE `ull_select_child`
(
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `ull_select_id` INTEGER,
  `caption_i18n_default` VARCHAR(64),
  `sequence` INTEGER,
  `creator_user_id` INTEGER,
  `created_at` DATETIME,
  `updator_user_id` INTEGER,
  `updated_at` DATETIME,
  PRIMARY KEY (`id`),
  INDEX `ull_select_child_FI_1` (`ull_select_id`),
  CONSTRAINT `ull_select_child_FK_1`
    FOREIGN KEY (`ull_select_id`)
    REFERENCES `ull_select` (`id`)
)Type=MyISAM';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'CREATE TABLE `ull_select_child_i18n`
(
  `caption_i18n` VARCHAR(64),
  `id` INTEGER  NOT NULL,
  `culture` VARCHAR(7)  NOT NULL,
  PRIMARY KEY (`id`,`culture`),
  CONSTRAINT `ull_select_child_i18n_FK_1`
    FOREIGN KEY (`id`)
    REFERENCES `ull_select_child` (`id`)
    ON DELETE CASCADE
)Type=MyISAM';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

// add options to field/column infos
$query = 'ALTER TABLE `ull_flow_field` ADD `options` TEXT after `ull_field_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_column_info` ADD `options` TEXT after `ull_field_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


// insert column info for select boxes
$x = new UllColumnInfo();
$x->setCulture('de');
$x->setDbTableName('ull_select_child');
$x->setDbColumnName('ull_select_id');
$x->setUllFieldId('5');
$x->setEnabled(true);
$x->setShowInList(true);
$x->setCaptionI18nDefault('Select Box');
$x->setCaptionI18n('SelectBox');
$x->save();

// create priority select box
$x= new UllSelect();
$x->setCulture('de');
$x->setSlug('priority');
$x->setCaptionI18nDefault('Priority');
$x->setCaptionI18n('Priorität');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();

// create priority select children
$x= new UllSelectChild();
$x->setCulture('de');
$x->setUllSelectId(1);
$x->setCaptionI18nDefault('Very high');
$x->setSequence(1000);
$x->setCaptionI18n('Sehr hoch');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();
$x= new UllSelectChild();
$x->setCulture('de');
$x->setUllSelectId(1);
$x->setCaptionI18nDefault('High');
$x->setSequence(2000);
$x->setCaptionI18n('Hoch');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();
$x= new UllSelectChild();
$x->setCulture('de');
$x->setUllSelectId(1);
$x->setCaptionI18nDefault('Normal');
$x->setSequence(3000);
$x->setCaptionI18n('Normal');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();
$x= new UllSelectChild();
$x->setCulture('de');
$x->setUllSelectId(1);
$x->setCaptionI18nDefault('Low');
$x->setSequence(4000);
$x->setCaptionI18n('Niedrig');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();
$x= new UllSelectChild();
$x->setCulture('de');
$x->setUllSelectId(1);
$x->setCaptionI18nDefault('Very low');
$x->setSequence(4000);
$x->setCaptionI18n('Sehr niedrig');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();

// add ull_fields
$x= new UllField();
$x->setCulture('de');
$x->setFieldType('select');
$x->setCaptionI18nDefault('Select Box');
$x->setCaptionI18n('Auswahlfeld');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();
$x= new UllField();
$x->setCulture('de');
$x->setFieldType('password');
$x->setCaptionI18nDefault('Password');
$x->setCaptionI18n('Passwort');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();
$password_field_id = $x->getId();

//add password field type to ull_user column info
$c = new Criteria();
$c->add(UllColumnInfoPeer::DB_TABLE_NAME, 'ull_user');
$c->add(UllColumnInfoPeer::DB_COLUMN_NAME, 'password');
$x = UllColumnInfoPeer::doSelectOne($c);
$x->setUllFieldId($password_field_id);
$x->save();


// create priority select box
$x= new UllFlowAction();
$x->setCulture('de');
$x->setSlug('send');
$x->setCaptionI18nDefault('Sent');
$x->setCaptionI18n('Abgeschickt');
$x->setCreatorUserId(1);
$x->setUpdatorUserId(1);
$x->save();