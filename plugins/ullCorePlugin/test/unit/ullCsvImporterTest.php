<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color);


$t->diag('__construct()');

  $path = dirname(__FILE__) . '/csv_test_semicolon.csv';

  $importer = new ullCsvImporter($path);
  
  $t->isa_ok($importer, 'ullCsvImporter');
  
  
$t->diag('getDelimiter()');

  $t->is($importer->getDelimiter(), ';', 'Automatically detects semicolon delimiter');
  
  
$t->diag('getHeaders()');

  $reference = array(
    'First name',
    'Last name',
    'Email',     
  );
  
  $t->is($importer->getHeaders(), $reference, 'Returns the correct headers');

  
$t->diag('toArray()');

  $reference = array(
    1 => array(
      'First name'  => 'Klemens',
      'Last name'   => 'Ullmann-Marx',
      'Email'       => 'k@ull.at',
    ),
    3 => array(
      'First name'  => "Poor \nGuy",
      'Last name'   => 'NoEmail',
      'Email'       =>  null
    ),
    4 => array(
      'First name'  => 'Trailing',
      'Last name'   => 'Charm',
      'Email'       => 'charm@example.com',
    ),
    5 => array(
      'First name'  => 'Email',
      'Last name'   => 'Error',
      'Email'       => 'error;fatal@invalid',
    ),
  );
  
//  var_dump($reference);
//  
//  var_dump($importer->toArray());
  
  $t->is($importer->toArray(), $reference, 'Return correct array format');
  
  
  
$t->diag('getDelimiter() with tabs delimiter');

  $path = dirname(__FILE__) . '/csv_test_tabs.csv';

  $importer = new ullCsvImporter($path);

  $t->is($importer->getDelimiter(), "\t", 'Automatically detects tab delimiter');  
  
  

$t->diag('toArray() with tabs');

  $reference = array(
    1 => array(
      'First name'  => 'Klemens',
      'Last name'   => 'Ullmann-Marx',
      'Email'       => 'k@ull.at',
    ),
  );    
  
  $t->is($importer->toArray(), $reference, 'Return correct array format');
  
  