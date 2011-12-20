<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

class ullDoctrineMapperTest extends ullDoctrineMapper
{
  
}

$t = new lime_test(6, new lime_output_color);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('__construct()');

  $data = array(
    // A new user
    1 => array(
      'First name'  => 'Klemens',
      'Last name'   => 'Ullmann-Marx',
      'Email'       => 'k@ull.at',
    ),
    // A new incomplete user
    3 => array(
      'First name'  => 'Trailing',
      'Last name'   => 'Charm',
      'Additional' => 'foobar',
    ),
    // An existing user with a changed name
    4 => array(
      'First name'  => 'Test',
      'Last name'   => 'User Updated',
      'Email'       => 'test.user@example.com',
    )
  );
  
  $mapping = array(
    'First name'    => 'first_name',
    'Last name'     => 'last_name',
    'Email'         => 'email',
    'Unsupported'   => 'unsupported',
  );
  
  $mapper = new ullDoctrineMapperTest($data, $mapping, 'UllUser');


$t->diag('getGenerator()');

  $t->isa_ok($mapper->getGenerator(), 'UllUserGenerator');
  

$t->diag('mapValidateAndSave()');

  $mapper->mapValidateAndSave();

  $user = Doctrine::getTable('UllUser')->findOneByEmail('k@ull.at');
  $t->is($user->first_name, 'Klemens', 'Klemens: first name ok');
  $t->is($user->last_name, 'Ullmann-Marx', 'Klemens: last name ok');
  
  $user = Doctrine::getTable('UllUser')->findOneByFirstName('Trailing');
  $t->is($user, null, 'Trailing: user was not imported');
  
  $user = Doctrine::getTable('UllUser')->findOneByEmail('test.user@example.com');
  $t->is($user->first_name, 'Test', 'Test: first name ok');
  $t->is($user->last_name, 'User Updated', 'Test: last name updated ok');
  

$t->diag('getGeneratorErrors()');

  $generatorErrors = $mapper->getGeneratorErrors();
  
  $t->is(count($generatorErrors), 1, 'We have one row with an error');
  
  $generator = reset($generatorErrors);
  $error = $generator->getForm()->offsetGet('email')->renderError();
  
  $t->is($error, 'Required', 'Returns the correct error');
  
  
  