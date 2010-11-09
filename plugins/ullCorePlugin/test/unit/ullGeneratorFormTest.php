<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}
// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new myTestCase(6, new lime_output_color, $configuration);

$t->diag('__construct()');

  $columnsConfig = ullColumnConfigCollection::buildFor('TestTable');
  
  $form = new ullGeneratorForm(new TestTable, $columnsConfig, 'edit');
  $t->isa_ok($form, 'ullGeneratorForm', '__construct() returns the correct object');
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullTable', 'The form uses the "ullTable" formatter by default');
  
  $form = new ullGeneratorForm(new TestTable, $columnsConfig);
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullList', 'The form uses the "ullList" formatter for list actions');

$t->diag('Mark mandatory fields');
  $form->getWidgetSchema()->offsetSet('one', new sfWidgetFormInput());
  $form->getWidgetSchema()->setLabel('one', 'One');
  $form->getValidatorSchema()->offsetSet('one', new sfValidatorString(array('required' => false)));
  
  $form->getWidgetSchema()->offsetSet('two', new sfWidgetFormDate());
  $form->getWidgetSchema()->setLabel('two', 'Two');
  $form->getValidatorSchema()->offsetSet('two', new sfValidatorDate(array('required' => true)));
  
  $form->getWidgetSchema()->offsetSet('three', new ullWidget());
  $form->getWidgetSchema()->setLabel('three', 'Three');
  $form->getValidatorSchema()->offsetSet('three', new sfValidatorPass(array('required' => true)));  
  
  $form->markMandatoryFields();
  
  $t->is($form->getWidgetSchema()->getLabel('one'), 'One', 'Correctly leaves a non required fields as it was');
  $t->is($form->getWidgetSchema()->getLabel('two'), 'Two *', 'Correctly marks a required field');
  $t->is($form->getWidgetSchema()->getLabel('three'), 'Three', 'Correctly leaves a field as it was, when it is a ullWidget and therefore readonly');
  
//$t->diag('removeUnusedValues()');
//
//  $form = new ullGeneratorForm(new TestTable);
//  
//  $columnConfig = array (
//        'widgetOptions'       => array(),
//        'widgetAttributes'    => array(),
//        'validatorOptions'    => array('required' => false),
//        'access'              => 'r',
//  );  
//  
//  $widget = new ullMetaWidgetInteger($columnConfig, $form);
//  $widget->addToFormAs('first_name');
//  $widget->addToFormAs('last_name');
//  $widget->addToFormAs('update_comment');
//  
// 
//  $postArray = array(
//    'first_name'  => 'Hugo',
//    'last_name'   => '',
//    'Translation' => array('de' => array()),
//  );
//  
//  $form->bindAndSave($postArray);
//  
//  $reference = array(
//    'first_name'  => 'Hugo',
//    'last_name'   => null,
//    'Translation' => array('de' => array()), 
//  );
//
// //TODO: getValues() doesn't work, because $form->updateObject() does not update $form->values
//  $t->is($form->getValues(), $reference, 'removes values which were not set in the POST request');