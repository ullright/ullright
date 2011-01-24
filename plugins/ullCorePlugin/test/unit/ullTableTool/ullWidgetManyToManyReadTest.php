<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase {}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping', 'ull'));

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('without prebuilt query');

  $adminUser = Doctrine::getTable('UllUser')->findOneByUserName('admin');
  $testUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
  $options = array('model' => 'UllUser', 'method' => 'display_name');
  $widget = new ullWidgetManyToManyRead($options);

  $t->is($widget->render('foo', array($testUser['id'], $adminUser['id'])), 'Master Admin, Test User');

$t->diag('with prebuilt query');

  $q = new Doctrine_Query();
  $q
    ->from('UllUser')
    ->orderBy('display_name desc')
  ;

  $options = array('model' => 'UllUser', 'query' => $q);
  $widget = new ullWidgetManyToManyRead($options);

$t->diag('->render()');
  
  $t->is($widget->render('foo', array($testUser['id'], $adminUser['id'])), 'Test User, Master Admin');
  