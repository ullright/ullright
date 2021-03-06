<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(11, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('Access to parent values via UllCloneUser object getters');
  $cloneUser = Doctrine::getTable('UllCloneUser')->findOneByComment('admin_clone_user');
  $t->is($cloneUser->phone_extension, '7777', 'returns the correct value for a native data');
  $t->is($cloneUser->UllJobTitle->name, 'Head of marketing', 'returns the correct value for relation native data');
  $t->is($cloneUser->fax_extension, '3333', 'returns the correct value for data retrieved from the parent');
  $t->is($cloneUser->UllDepartment->name, 'Information Technology', 'returns the correct value for relation data retrieved from the parent');  
  
  
  
$t->diag('Access to parent values via UllEntity object->toArray()');
  $cloneUser = Doctrine::getTable('UllEntity')->findOneByComment('admin_clone_user');
  $array = $cloneUser->toArray();
  $t->is($array['phone_extension'], '7777', 'returns the correct value for a native data');
  $t->is($array['fax_extension'], '3333', 'returns the correct value for data retrieved from the parent');

  
$t->diag('Access to parent values via a query using select and basing on UllEntity');  
  $q = new Doctrine_Query;
  $q
    ->select('u.phone_extension, u.fax_extension')
    ->addSelect('u.*') // possible doctrine bug, see ullQueryTest
    ->from('UllEntity u')
    ->where('u.comment = ?', 'admin_clone_user')
  ;
  $cloneUser = $q->fetchOne();
  
  $t->is($cloneUser->phone_extension, '7777', 'returns the correct value for native data');
  $t->is($cloneUser->UllJobTitle->name, 'Head of marketing', 'returns the correct value for native relation data');
  $t->is($cloneUser->fax_extension, '3333', 'returns the correct value for data retrieved from the parent');
  $t->is($cloneUser->UllDepartment->name, 'Information Technology', 'returns the correct value for relation data retrieved from the parent');

  
$t->diag('Set / check for invalid columns');
  $cloneUser = new UllCloneUser;
  $cloneUser->comment = 'Foobar clone user comment';
  try
  {
    $cloneUser->username = 'luzifer';
    $t->fail('Doesn\'t throw an exception when setting an invalid column');
  } 
  catch (Exception $e)
  {
    $t->pass('Throws an exception when setting an invalid column');
  } 
  