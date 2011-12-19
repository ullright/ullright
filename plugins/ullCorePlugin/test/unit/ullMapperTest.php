<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(6, new lime_output_color);

$t->diag('__construct()');

  $data = array(
    1 => array(
      'First name'  => 'Klemens',
      'Last name'   => 'Ullmann-Marx',
      'Email'       => 'k@ull.at',
      'Mailing list'=> 'Product news',
    ),
    3 => array(
      'First name'  => 'Trailing',
      'Last name'   => 'Charm',
      'Invalid column' => 'foobar',
    ),
  );
  
  $mapping = array(
    'First name'    => 'first_name',
    'Last name'     => 'last_name',
    'Email'         => 'email',
    'Mailing list'  => 'UllNewsletterMailingList'
  );
  
  $mapper = new ullMapper($data, $mapping);


$t->diag('hasErrors() before doMapping()');  
  
  $t->is($mapper->hasErrors(), false, 'No errors');
  
  
 $t->diag('doMapping()');   
  
  $reference = array(
    1 => array(
      'first_name'  => 'Klemens',
      'last_name'   => 'Ullmann-Marx',
      'email'       => 'k@ull.at',
      'UllNewsletterMailingList' => 'Product news',
    ),
    3 => array(
      'first_name'  => 'Trailing',
      'last_name'   => 'Charm',
      'email'       => null,
      'UllNewsletterMailingList' => null,
    )
  );
  
  $t->is($mapper->doMapping(), $reference, 'Performs the correct mapping');
  
  
$t->diag('has/getErrors() after doMapping() with errors'); 
  
  $t->is($mapper->hasErrors(), true, 'Now we have errors');
  
  $reference = array(
    'Invalid column' => 'Warning: no column "Invalid column" found',
  );
  
  $t->is($mapper->getErrors(), $reference, 'Returns the correct error');

  
$t->diag('getMappingSourceFields()');  
  
  $reference = array(
    'First name',
    'Last name',
    'Email',
    'Mailing list',
  );
  
  $t->is($mapper->getMappingSourceFields(), $reference, 'Return the correct source columns');  
  
  
$t->diag('getEmptyTargetLine()');

  $reference = array(
    'first_name'    => null,
    'last_name'     => null,
    'email'         => null,
    'UllNewsletterMailingList'  => null,
  );
  
  $t->is($mapper->getEmptyTargetLine(), $reference, 'Returns the correct result');
  