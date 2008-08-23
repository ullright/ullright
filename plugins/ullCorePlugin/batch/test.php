<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('myApp', 'cli', true);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);

var_dump(md5("test"));
exit();

$user = new User();
$user->first_name = "Klemens";
$user->save();
//
//
//$users = Doctrine::getTable('UllUser')->findByFirstName('Klemens');
///*@var $user User*/
//$user = $users->getFirst();
//echo $user->first_name;
//
//$user->UllGroup[0]->caption = 'Test';
//$user->UllGroup[1]->caption = 'Second Test';
//$user->save();

// delete the relation (the rows in the many to many table);

//$q = new Doctrine_Query;
//$q->from('UllUser u')
//  ->where('first_name = ?', 'Klemens')
//;
//$user = $q->fetchOne();
//
//$user->last_name = 'Ullmann-Marx';
//$user->save();

$group = new UllGroup;
$group->caption = 'Hallo';
$group->Creator = $user;
$group->save();
