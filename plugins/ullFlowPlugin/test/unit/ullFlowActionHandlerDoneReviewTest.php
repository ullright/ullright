<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->diag('__construct()');

  $handler = new ullFlowActionHandlerDoneReview(new ullFlowGenerator());
  
  
  $t->isa_ok($handler, 'ullFlowActionHandlerDoneReview', 'returns the correct object');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=done_review" value="Done, assign to creator for review" />';
  $t->is($handler->render(), $reference, 'returns the correct html code');
  
//TODO: test getNext()  
  