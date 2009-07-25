<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test {}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(22, new lime_output_color, $configuration);

$t->diag('__construct');

  sfContext::getInstance()->getRequest()->getParameterHolder()->remove('action');
  $base = new ullGeneratorBase();
  $t->isa_ok($base, 'ullGeneratorBase', 'Returns the correct object');
  $t->is($base->getDefaultAccess(), 'r', 'Sets the correct default access');
  $t->is($base->getRequestAction(), 'list', 'Sets the correct request action');
  
  try
  {
    $base = new ullGeneratorBase('y');
    $t->fail('Doesn\'throw an exception when trying to given an invalid default access');  
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when trying to given an invalid default access');
  }
  
  $base = new ullGeneratorBase('w');
  $t->is($base->getDefaultAccess(), 'w', 'Sets a given access type correctly');
  
  $base = new ullGeneratorBase(null, 'myAction');
  $t->is($base->getRequestAction(), 'myAction', 'Sets a given request action correctly');
  $t->is($base->getDefaultAccess(), 'w', 'Guesses the default access depending on the request action correctly');

  sfContext::getInstance()->getRequest()->setParameter('action', 'create');
  $base = new ullGeneratorBase;
  $t->is($base->getRequestAction(), 'create', 'Uses the symfony request action per default');
  $t->is($base->getDefaultAccess(), 'w', 'Guesses the default access depending on the request action correctly');  
  
$t->diag('isXXXAction()');
  sfContext::getInstance()->getRequest()->getParameterHolder()->remove('action');
  $base = new ullGeneratorBase;
  $t->is($base->isListAction(), true, 'Default is list action');
  $t->is($base->isCreateAction(), false, 'Default is not create action');
  $t->is($base->isEditAction(), false, 'Default is not edit action');
  $t->is($base->isCreateOrEditAction(), false, 'True for create or edit');
  $base->setRequestAction('edit');
  $t->is($base->isEditAction(), true, 'Is edit action');
  $t->is($base->isCreateAction(), false, 'Edit is not create action');
  $t->is($base->isListAction(), false, 'Edit is not list action');
  $t->is($base->isCreateOrEditAction(), true, 'Edit is edit or create action'); 
  $t->is($base->getDefaultAccess(), 'w', 'setRequestAction() also guesses the default access depending on the request action correctly');
  
$t->diag('isAction()');
  sfContext::getInstance()->getRequest()->getParameterHolder()->remove('action');
  $base = new ullGeneratorBase;
  $t->is($base->isAction('list'), true, 'Default is list action');
  $t->is($base->isAction('create'), false, 'Default is not create action');
  $t->is($base->isAction(array('list', 'show')), true, 'True for list or show');      
  $t->is($base->isAction(array('create', 'edit')), false, 'False for create or edit');
  
  
  
   