<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array(
    'my_subject' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => true),
        'label'               => 'My custom subject label',
        'metaWidget'          => 'ullMetaWidgetLink',
        'access'              => 'w',
        'is_in_list'          => false,
        ),
     'my_information_update' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'My information update',
        'metaWidget'          => 'ullMetaWidgetInformationUpdate',
        'access'              => 'w',
        'is_in_list'          => false,
        ),             
    'my_datetime' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Date',
        'metaWidget'          => 'ullMetaWidgetDateTime',
        'access'              => 'w',
        'is_in_list'          => false,
        ),                  
    'my_email' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Your email address',
        'metaWidget'          => 'ullMetaWidgetEmail',
        'access'              => 'w',
        'is_in_list'          => false,
        ),
    'column_priority' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Priority',
        'metaWidget'          => 'ullMetaWidgetPriority',
        'access'              => 'w',
        'is_in_list'          => false,
        'default_value'       => '3',
        ),        
    'upload' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Attachments',
        'metaWidget'          => 'ullMetaWidgetUpload',
        'access'              => 'w',
        'is_in_list'          => false,
        ),
    'wiki_link' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Wiki links',
        'metaWidget'          => 'ullMetaWidgetWikiLink',
        'access'              => 'w',
        'is_in_list'          => false,
        ),                 
    'column_tags' => array (
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array('required' => false),
        'label'               => 'Tags',
        'metaWidget'          => 'ullMetaWidgetTaggable',
        'access'              => 'w',
        'is_in_list'          => false,
        ),                  
  );

  public function getColumnsConfigMock()
  {
    return $this->columnsConfigMock;
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('I18N');

$t = new myTestCase(24, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$app = Doctrine::getTable('UllFlowApp')->find(1);
$docs = Doctrine::getTable('UllFlowDoc')->findByUllFlowAppId(1);

$t->begin('__construct()');

  $generator = new ullFlowGenerator;
  $t->isa_ok($generator, 'ullFlowGenerator', '__construct() returns the correct object without params');
  
  $generator = new ullFlowGenerator($app, 'w');
  $t->isa_ok($generator, 'ullFlowGenerator', '__construct() returns the correct object');

$t->diag('getTableConfig()');
  $tableConfig = $generator->getTableConfig();
  $t->isa_ok($tableConfig, 'UllFlowApp', 'tableConfig is the correct object');  
  $t->is($tableConfig->label, 'Trouble ticket tool', 'Label is correct'); 
  
$t->diag('getColumnConfig()');
  $columnsConfig = $generator->getColumnsConfig();
  $t->is(is_array($columnsConfig), true, 'columnsConfig is an array');
  $t->is(count($columnsConfig), 8, 'columnsConfig has the correct number of columns');
  
  // don't use foreach because it ignores the ordering of the fields  
  $mocks = $t->getColumnsConfigMock();
  while (list($key, $val) = each($columnsConfig))
  {
    $columnConfig = array($key => $val);
    
    list($key, $val) = each($mocks);
    $mock = array($key => $val);
    
    $t->is($columnConfig, $mock, 'columnConfig for column "' . key($columnConfig) . '" is correct');
  }

$t->diag('buildForm()');

  $generator->buildForm($docs);  
  
$t->diag('getForm() with calling buildForm() prior');  

  $form = $generator->getForm();
  $t->isa_ok($form, 'ullFlowForm', 'getForm() returns the correct object');
  
$t->diag('getForms()');

  $forms = $generator->getForms();
  $t->is(is_array($forms), true, 'getForms() returns an array');
  $t->is(count($forms), 2, 'getForms returns the correct number of forms');
  $t->isa_ok($forms[0], 'ullFlowForm', 'The first entry is the correct object');  
  $t->isa_ok($forms[1], 'ullFlowForm', 'The second entry is the correct object');  
  
$t->diag('buildListOfUllFlowActionHandlers()');

  $doc = Doctrine::getTable('UllFlowDoc')->find(2);
  $generator = new ullFlowGenerator($app, 'w');
  $generator->buildForm($doc);
  
  $generator->buildListOfUllFlowActionHandlers();
  $form = $generator->getForm();
  $t->is(count($form->getWidgetSchema()->getFields()), 10, 'The form now contains one more field from the action handler');

$t->diag('getListOfUllFlowActionHandlers()');

  $handlers = $generator->getListOfUllFlowActionHandlers();
  $t->is(count($handlers), 2, 'Two UllFlowActionHandler were set');
  $t->isa_ok($handlers['assign_to_user'], 'ullFlowActionHandlerAssignToUser', 'The first field handler returns the correct object');
  $t->isa_ok($handlers['close'], 'ullFlowActionHandlerClose', 'The second field handler returns the correct object');
  
$t->diag('setUllFlowActionHandler()');

  $doc = Doctrine::getTable('UllFlowDoc')->find(2);
  $generator = new ullFlowGenerator($app, 'w');
  $generator->buildForm($doc);
  
  $generator->setUllFlowActionHandler('assign_to_user');
  $form = $generator->getForm();  
  
$t->diag('getUllFlowActionHandler()');

  $t->isa_ok($generator->getUllFlowActionHandler(), 'ullFlowActionHandlerAssignToUser', 'Returns the correct object');  
  