<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new sfDoctrineTestCase(11, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

/**
 * Test implementation of ullDoctrineMapper
 *
 */
class ullDoctrineMapperTest extends ullDoctrineMapper
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullDoctrineMapper::getGenerator()
   */
  public function getGenerator()
  {
    $generator = new ullUserGenerator('w');
    $generator->getColumnsConfig()->disableAllExcept($this->mapping);
    $generator->getColumnsConfig()->offsetGet('email')->setIsRequired(true);  
    
    return $generator;
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullDoctrineMapper::getObject()
   */
  public function getObject(array $row)
  {
    $email = $row['email'];

    // Warning: this means a new user is created when no email address
    //   (=key) is found. Therefore the validation must require the email
    //   address
    if ($email)
    {
      $user = Doctrine::getTable('UllUser')->findOneByEmail($email);
      
      if (!$user)
      {
        $user = new UllUser;
      }
    }
    else 
    {
      $user = new UllUser;
    }
    
    return $user;
  }
  
}  


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
  );
  
  $mapper = new ullDoctrineMapperTest($data, $mapping);

$t->diag('getNumberImported()');

  $t->is($mapper->getNumberImported(), 0, 'Nothing imported yet');
  
$t->diag('getGeneratorErrors()');

  $t->is($mapper->getGeneratorErrors(), array(), 'No errors yet');

$t->diag('getGenerator()');

  $t->isa_ok($mapper->getGenerator(), 'ullUserGenerator', 'Returns the correct object');
  
  
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
  

$t->diag('getNumberImported() after mapping');

  $t->is($mapper->getNumberImported(), 2, '2 rows imported');
  

$t->diag('getGeneratorErrors() after mapping');

  $generatorErrors = $mapper->getGeneratorErrors();
  
  $t->is(count($generatorErrors), 1, 'We have one row with an error');
  
  $generator = reset($generatorErrors);
  $error = $generator->getForm()->offsetGet('email')->renderError();
  $t->is(strpos($error, 'Required'), true, 'Returns the correct error');
  
  
  

