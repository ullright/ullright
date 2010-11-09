<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();

$t = new lime_test(41, new lime_output_color);

$t->diag('sluggify()');

    $t->is(ullCoreTools::sluggify('foobar'), 'foobar', 'lowercase word stay the same');
    $t->is(ullCoreTools::sluggify('Foo bar'), 'foo_bar', 'correctly transformes spaces and uppercase letters');
    $t->is(ullCoreTools::sluggify('Föo bar#@$'), 'f__o_bar___', 'correctly transformes special chars');
  
    
$t->diag('order_array_by_array()');
  $test = array(
    'apple' => array('type' => 'fruit'),
    'orange' => 'Can be squashed',
    'pear' => array('type' => 'fruit', 'color' => 'yellow-green')
  );
  
  $reference = array(
    'orange' => 'Can be squashed',
    'pear' => array('type' => 'fruit', 'color' => 'yellow-green'),
    'apple' => array('type' => 'fruit')
  );
  
  $t->is(ullCoreTools::orderArrayByArray($test, array('orange', 'pear')), $reference, 'Orders the given array correctly');
  try
  {
    ullCoreTools::orderArrayByArray($test, array('foobar'));
    $t->fail('Doesn\'t throw an exception for an invalid key');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for an invalid key');
  }
  
  
$t->diag('timeToString()');
  $t->is(ullCoreTools::timeToString(0), '0:00', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(60), '0:01', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(3600), '1:00', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(5400), '1:30', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(-0), '0:00', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(-60), '- 0:01', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(-3600), '- 1:00', 'Returns the correct string');
  $t->is(ullCoreTools::timeToString(-5400), '- 1:30', 'Returns the correct string');
  
$t->diag('humanTimeToIsoTime()');
  $t->is(ullCoreTools::humanTimeToIsoTime('0:00'), '00:00:00', 'Returns the correct string');
  $t->is(ullCoreTools::humanTimeToIsoTime('1:00'), '01:00:00', 'Returns the correct string');
  $t->is(ullCoreTools::humanTimeToIsoTime('1:01'), '01:01:00', 'Returns the correct string');
  $t->is(ullCoreTools::humanTimeToIsoTime('24:59'),'24:59:00', 'Returns the correct string');
  
$t->diag('isoTimeToHumanTime()');
  $t->is(ullCoreTools::isoTimeToHumanTime('00:00:00'), '0:00', 'Returns the correct string');
  $t->is(ullCoreTools::isoTimeToHumanTime('01:01:00'), '1:01', 'Returns the correct string');
  $t->is(ullCoreTools::isoTimeToHumanTime('10:10:00'), '10:10', 'Returns the correct string');

$t->diag('appendParamsToUri()');
  $t->is(ullCoreTools::appendParamsToUri('ullUser/edit', 'id=2'), 'ullUser/edit?id=2', 'Creates the correct uri');
  $t->is(ullCoreTools::appendParamsToUri('ullTableTool/edit?table=UllLocation', 'id=2'), 'ullTableTool/edit?table=UllLocation&id=2', 'Creates the correct uri');
  
  
$t->diag('debugArrayWithDoctrineRecords()');

  $user = new UllUser();
  $user->display_name = 'Penny Parker';

  $array = array(
    'foo' => 'bar',
    'baz' => array (
      'number' => 123,
      'normal_object' => new stdClass(),
      'doctrine_record' => $user,
    ),
    'doctrine_record' => new UllGroup(),
  );
  
  $reference = array(
    'foo' => 'bar',
    'baz' => array (
      'number' => 123,
      'normal_object' => 'Object "stdClass"',
      'doctrine_record' => 'Object "UllUser" with __toString() value: "Penny Parker"',
    ),
    'doctrine_record' => 'Object "UllGroup"',
  );
  
  $t->is(ullCoreTools::debugArrayWithDoctrineRecords($array), $reference, 'Returns the correct array');
  
  
$t->diag('prepareCsvColumn()');
  $t->is(ullCoreTools::prepareCsvColumn(''), '', 'Returns the same value');
  $t->is(ullCoreTools::prepareCsvColumn('49'), '49', 'Does not quotes a numeric value');  
  $t->is(ullCoreTools::prepareCsvColumn('foo'), '"foo"', 'Quotes a normal string');
  $t->is(ullCoreTools::prepareCsvColumn('He\'s on fire'), '"He\'s on fire"', 'Quotes a string containing single quotes');
  $t->is(ullCoreTools::prepareCsvColumn('He is on "fire"'), '"He is on ""fire"""', 'Escapes double quotes');
  $t->is(ullCoreTools::prepareCsvColumn('<div class="foo">bar</div>'), '"bar"', 'Strips tags');
  $t->is(ullCoreTools::prepareCsvColumn('Am&uuml;sant'), '"' . utf8_decode('Amüsant') . '"', 'Decodes html entities');
  $t->is(ullCoreTools::prepareCsvColumn('&gt;'), '">"', 'Decodes html entities');
  $t->is(ullCoreTools::prepareCsvColumn('&#039;'), '"\'"', 'Decodes &#039; html entity');

 
$t->diag('walkDirectory()');

  //define a test callback
  function walkDirectoryCallbackTest($path, $file, $param)
  {
    $param[] = $file;
    return false;
  }
  
  //create temp directory with random name, add some files
  $filesystem = new sfFilesystem();
  $tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR .
    'ullCoreTest' . mt_rand(10000, 99999) . DIRECTORY_SEPARATOR;
  $tempFiles = array($tempPath . 'temp.jPg', $tempPath . 'temp.jpeg', $tempPath . 'temp');
   
  $filesystem->mkdirs($tempPath);
  $filesystem->touch($tempFiles);
  
  //just walk the directory and see if every file gets handled
  $handledFiles = array();
  $cleared = ullCoreTools::walkDirectory($tempPath, 'walkDirectoryCallbackTest', &$handledFiles);
  $t->is_deeply($cleared, array(), 'Returns empty array');
  $t->is_deeply($handledFiles, array('temp', 'temp.jpeg', 'temp.jPg'), 'Returns handles files');

$t->diag('deleteFileIfNotExtension');

  $cleared = ullCoreTools::walkDirectory($tempPath, 'ullCoreTools::deleteFileIfNotExtension', array('jpg'));
  $t->is_deeply($cleared, array('temp', 'temp.jpeg'), 'Returns correct array of cleaned files');
  
$t->diag('deleteFileIfNotFromType');
  //copy valid test image to temp dir
  $resourceFileName = 'ullCoreToolsTest_resource.png';
  $resourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $resourceFileName;
  $filesystem->copy($resourcePath, $tempPath . DIRECTORY_SEPARATOR . $resourceFileName);
  
  //recreate invalid test files
  $filesystem->touch($tempFiles);
  
  $cleared = ullCoreTools::walkDirectory($tempPath, 'ullCoreTools::deleteFileIfNotFromType', array('PNG'));
  $t->is_deeply($cleared, array('temp', 'temp.jpeg', 'temp.jPg'), 'Returns correct array of cleaned files');
  
  //clean up (because that is the polite thing to do!)
  
  //if the above method did not unlink files correctly,
  //we get a warning here, which is good
  $filesystem->remove(array($tempPath . $resourceFileName));
  $filesystem->remove($tempPath);
  
  
$t->diag('esc_decode()');
  $t->is(ullCoreTools::esc_decode(''), '', 'Correctly retains an empty string');  
  $t->is(ullCoreTools::esc_decode('foo bar'), 'foo bar', 'Correctly retains an normal string');
  $t->is(ullCoreTools::esc_decode('Grüße'), 'Grüße', 'Correctly retains an string with german umlauts');
  $t->is(ullCoreTools::esc_decode('Gr&uuml;&szlig;e'), 'Grüße', 'Correctly decodes html umlaut entities with the correct charset');
  $t->is(ullCoreTools::esc_decode('&lt;br /&gt;'), '<br />', 'Correctly decodes a tag');