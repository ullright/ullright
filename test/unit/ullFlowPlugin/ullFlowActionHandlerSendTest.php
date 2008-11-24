<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');
  $handler = new ullFlowActionHandlerSend;
  $t->isa_ok($handler, 'ullFlowActionHandlerSend', 'returns the correct object');
  
$t->diag('getEditWidget()');
  
  $reference = '<input type="submit" name="submit_send" value="Send" />';
  $t->is($handler->getEditWidget(), $reference, 'returns the correct html code');  
  
