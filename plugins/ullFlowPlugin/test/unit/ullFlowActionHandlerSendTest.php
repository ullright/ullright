<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(2, new lime_output_color, $configuration);

$t->diag('__construct');

  $form = new ullFlowForm(new UllFlowDoc(), new ullColumnConfigCollection('ullFlowDoc'));
  $handler = new ullFlowActionHandlerSend($form);
  
  $t->isa_ok($handler, 'ullFlowActionHandlerSend', 'returns the correct object');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=send" value="Send" />';
  $t->is($handler->render(), $reference, 'returns the correct html code');