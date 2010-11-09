<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase {}

/**
 * Test implementation of abstract class ullFlowRule
 *
 */
class ullFlowRuleTest extends ullFlowRule
{
  public function getNext()
  {
    $next['step']    = $this->findStep('trouble_ticket_dispatcher');
    $next['entity']  = $this->findGroup('Helpdesk');

    return $next;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(11, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$helpdeskGroupId = UllGroupTable::findIdByDisplayName('Helpdesk');

$t->begin('__construct()');

  $doc = Doctrine::getTable('UllFlowDoc')->findOneBySubject('My first trouble ticket');
  $rule = new ullFlowRuleTest($doc);

$t->diag('findGroup()');

  $t->is($rule->findGroup('Helpdesk')->id, $helpdeskGroupId, 'returns the correct group');

$t->diag('findStep()');

  $t->is($rule->findStep('trouble_ticket_creator')->id, 1, 'returns the correct slug');

$t->diag('isStep()');

  $t->ok($rule->isStep('trouble_ticket_creator'), 'true for the current step');    
  $t->is($rule->isStep('Foobar'), false, 'false for any other step');
  
$t->diag('isAction()');

  $t->ok($rule->isAction('create'), 'true for the current action');    
  $t->is($rule->isStep('close'), false, 'false for any other action');  
  
$t->diag('getNext()');

  $next = $rule->getNext();
  $t->isa_ok($next['step'], 'UllFlowStep', 'returns the correct object for next step');
  $t->is($next['step']->id, 2, 'return the correct step id');
  $t->isa_ok($next['entity'], 'UllGroup', 'returns the correct object for next entity');
  $t->is($next['entity']->id, $helpdeskGroupId, 'return the correct entity id');
  
$t->diag('findSuperior()');

  $doc->UllEntity = UllUserTable::findByDisplayName('Test User');
  $doc->save();
  $rule = new ullFlowRuleTest($doc);

  $t->is($rule->findSuperior()->id, UllUserTable::findByDisplayName('Master Admin')->id, 'returns the correct superior');