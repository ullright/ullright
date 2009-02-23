<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
}
// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);

//$form = new ullTableToolForm;

$t->diag('__construct()');

  $form = new ullGeneratorForm(new TestTable, 'edit');
  $t->isa_ok($form, 'ullGeneratorForm', '__construct() returns the correct object');
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullTable', 'The form uses the "ullTable" formatter by default');
  
  $form = new ullGeneratorForm(new TestTable);
  $t->is($form->getWidgetSchema()->getFormFormatterName(), 'ullList', 'The form uses the "ullList" formatter for list actions');

  
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