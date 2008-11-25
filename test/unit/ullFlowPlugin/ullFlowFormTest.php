<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('ull');

$t = new myTestCase(11, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);



$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $form = new ullFlowForm($doc, 'edit');
  $t->isa_ok($form, 'ullFlowForm', 'returns the correct object type');
  
  $columnConfig = array (
    'widgetOptions'       => array(),
    'widgetAttributes'    => array(),
    'validatorOptions'    => array('required' => false),
    'label'               => 'Your email address',
    'metaWidget'          => 'ullMetaWidgetEmail',
    'access'              => 'w',
    'show_in_list'        => true,
  );         
  $widget = new ullMetaWidgetEmail($columnConfig);
  $form->addUllMetaWidget('my_email', $widget);
  
  $defaults = $form->getDefaults();
  $t->is($defaults['my_email'], 'quasimodo@ull.at', 'The form returns the correct defaults');

$t->diag('save() with the default action ("save_close")');
  
  $request = array(
    'my_email'        => 'luke.skywalker@ull.at',
    'memory_comment'  => 'may the force be with you',
  );
  
  $form->bindAndSave($request);
  $t->ok($form->isValid(), 'the form is valid');
  
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc->my_email, 'luke.skywalker@ull.at', 'saves the virtual column values correctly');
  $t->is($doc->assigned_to_ull_entity_id, 1, 'leaves the assigned to entity as is was');
  $t->is($doc->assigned_to_ull_flow_step_id, 1, 'leaves the assigned to step as is was');
  $t->is($doc->UllFlowMemories[2]->comment, 'may the force be with you', 'The form saves the memory comment correctly');
  
$t->diag('save() with action "send"');

  sfContext::getInstance()->getRequest()->setParameter('submit_send', true);
  
  $request = array();
  
  $form->bindAndSave($request);
  $t->ok($form->isValid(), 'the form is valid');
  
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc->ull_flow_action_id, 8, 'sets the action correctly');
  $t->is($doc->assigned_to_ull_entity_id, 8, 'sets the entity correctly');
  $t->is($doc->assigned_to_ull_flow_step_id, 2, 'sets the step correctly');
 