<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct()');

  $form = new ullFlowForm(Doctrine::getTable('UllFlowDoc')->find(1));
  $handler = new ullFlowActionHandlerReturn($form);
  
  $t->isa_ok($handler, 'ullFlowActionHandlerReturn', 'returns the correct object');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=return" value="Return" />';
  $t->is($handler->render(), $reference, 'returns the correct html code');
  
$t->diag('getHistoryOneStepBack()');

  $form = new ullFlowForm(Doctrine::getTable('UllFlowDoc')->find(2));
  $handler = new ullFlowActionHandlerReturn($form);
  $next = $handler->getHistoryOneStepBack();
  
  $t->is($next['entity']->id, 1, 'return the correct UllEntity');
  $t->is($next['step']->id, 2, 'return the correct step');
  
  