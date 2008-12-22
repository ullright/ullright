<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();

$t = new lime_test(3, new lime_output_color);

$t->diag('sluggify()');

    $t->is(ullCoreTools::sluggify('foobar'), 'foobar', 'lowercase word stay the same');
    $t->is(ullCoreTools::sluggify('Foo bar'), 'foo_bar', 'correctly transformes spaces and uppercase letters');
    $t->is(ullCoreTools::sluggify('Föo bar#@$'), 'f__o_bar___', 'correctly transformes special chars');
  
