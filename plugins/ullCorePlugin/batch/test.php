<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('myApp', 'cli', true);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);


//$user = new User();
//$user->first_name = "Klemens";
//$user->save();
//
//
//$users = Doctrine::getTable('User')->findByFirstName('Klemens');
///*@var $user User*/
//$user = $users->getFirst();
//echo $user->first_name;
//
//$user->Group[0]->caption = 'Test';
//$user->Group[1]->caption = 'Second Test';
//$user->save();

// delete the relation (the rows in the many to many table);

$q = new Doctrine_Query;
$q->from('User u')
  ->where('first_name = ?', 'Klemens')
;
$user = $q->fetchOne();

$user->last_name = 'Ullmann-Marx';
$user->save();

