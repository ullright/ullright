<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
sfContext::getInstance()->getConfiguration()->loadHelpers('ull');
// manually require rule file, since it isn't found by the unit test
require(sfConfig::get('sf_apps_dir') . '/frontend/modules/ullFlow/lib/ullFlowRuleTroubleTicket.class.php');

$t = new myTestCase(11, new lime_output_color, $configuration);
//$t->setMode('yml_fixtures');
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $helpdeskGroupId = UllGroupTable::findIdByDisplayName('Helpdesk');

  $doc = new UllFlowDoc();
  $doc->ull_flow_app_id = 1;
  $doc->my_subject = 'Mariazell';
  $doc->my_project = 1;
  $doc->my_email = 'quasimodo@ull.at';
  $doc->save();
  
  $generator = new UllFlowGenerator($doc->UllFlowApp, $doc, 'w', 'edit');
  $generator->buildForm($doc);
  $form = $generator->getForm();
  $t->isa_ok($form, 'ullFlowForm', 'returns the correct object type');
  
//  $columnConfig = new ullColumnConfiguration();
//  $columnConfig->setLabel('Your email address');
//  $columnConfig->setMetaWidgetClassName('ullMetaWidgetEmail');
//       
//  $widget = new ullMetaWidgetEmail($columnConfig, $form);
//  $widget->addToFormAs('my_email');
  
  $defaults = $form->getDefaults();
  $t->is($defaults['my_email'], 'quasimodo@ull.at', 'The default values are loaded');

$t->diag('save() with the default action ("save_close")');
  
  $request = array(
    'my_subject'      => $doc->my_subject, //required
    'my_project'      => $doc->my_project, //required
    'my_email'        => 'luke.skywalker@ull.at',
    'memory_comment'  => 'may the force be with you',
  );
  
  $form->bindAndSave($request);
  $t->ok($form->isValid(), 'the form is valid');
  
  $t->clearTables();
  
  $doc = Doctrine::getTable('UllFlowDoc')->find(5);
  $t->is($doc->my_email, 'luke.skywalker@ull.at', 'saves the virtual column values correctly');
  $t->is($doc->assigned_to_ull_entity_id, 1, 'leaves the assigned to entity as is was');
  $t->is($doc->assigned_to_ull_flow_step_id, 1, 'leaves the assigned to step as is was');
  $t->is($doc->UllFlowMemories[2]->comment, 'may the force be with you', 'The form saves the memory comment correctly');
  
$t->diag('save() with action "send"');

  sfContext::getInstance()->getRequest()->setParameter('action_slug', 'send');
  
  $request = array(
    'my_subject'      => $doc->my_subject, //required
    'my_project'      => $doc->my_project, //required
  );
  
  $form->bindAndSave($request);
  $t->ok($form->isValid(), 'the form is valid');
  
  $t->clearTables();
  
  $doc = Doctrine::getTable('UllFlowDoc')->find(5);
  $t->is($doc->ull_flow_action_id, 8, 'sets the action correctly');
  $t->is($doc->assigned_to_ull_entity_id, $helpdeskGroupId, 'sets the entity correctly');
  $t->is($doc->assigned_to_ull_flow_step_id, 2, 'sets the step correctly');
 