<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
  protected $columnsConfigMock = array();
  
  public function initialize() 
  {
    $columnConfig = new ullColumnConfiguration('my_subject');
    $columnConfig->setValidatorOptions(array('required' => true));
    $columnConfig->setLabel('My custom subject label');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetString');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_subject'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_information_update');
    $columnConfig->setLabel('My information update');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetInformationUpdate');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_information_update'] = $columnConfig;
    
    //$columnConfig = new ullColumnConfiguration('my_date');
    //$columnConfig->setLabel('Date');
    //$columnConfig->setMetaWidgetClassName('ullMetaWidgetDate');
    //$columnConfig->setIsInList(false);
    //$this->columnsConfigMock['my_date'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_email');
    $columnConfig->setLabel('Your email address');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetEmail');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_email'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_priority');
    $columnConfig->setLabel('Priority');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetPriority');
    $columnConfig->setIsInList(false);
    $columnConfig->setDefaultValue('3');
    $this->columnsConfigMock['my_priority'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_upload');
    $columnConfig->setLabel('Attachments');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUpload');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_upload'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_wiki_link');
    $columnConfig->setLabel('Wiki links');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetWikiLink');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_wiki_link'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_project');
    $columnConfig->setWidgetOptions(array('add_empty' => 1, 'model' => 'UllProject'));
    $columnConfig->setValidatorOptions(array('required' => true));
    $columnConfig->setLabel('Project');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllProject');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_project'] = $columnConfig;
    
    $columnConfig = new ullColumnConfiguration('my_due_date');
    $columnConfig->setLabel('Due date');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetDateTime');
    $columnConfig->setIsInList(true);
    $this->columnsConfigMock['my_due_date'] = $columnConfig;    
    
    $columnConfig = new ullColumnConfiguration('my_tags');
    $columnConfig->setLabel('Tags');
    $columnConfig->setMetaWidgetClassName('ullMetaWidgetTaggable');
    $columnConfig->setIsInList(false);
    $this->columnsConfigMock['my_tags'] = $columnConfig;
  } 

  public function getColumnsConfigMock()
  {
    return $this->columnsConfigMock;
  }
  
  public function compareSingleColumnConfig($columnConfig, $columnConfigMock)
  {
    $this->diag('Now comparing: ' . $columnConfig->getColumnName());
    
    //compare some of the more common values
    $this->is_deeply($columnConfig->getWidgetOptions(), $columnConfigMock->getWidgetOptions(), 'widget options ok');
    $this->is_deeply($columnConfig->getWidgetAttributes(), $columnConfigMock->getWidgetAttributes(), 'widget attributes ok');
    $this->is_deeply($columnConfig->getValidatorOptions(), $columnConfigMock->getValidatorOptions(), 'validator attributes ok');
    $this->is($columnConfig->getLabel(), $columnConfigMock->getLabel(), 'label ok');
    $this->is($columnConfig->getMetaWidgetClassName(), $columnConfigMock->getMetaWidgetClassName(), 'meta widget class name ok');
    $this->is($columnConfig->getAccess(), $columnConfigMock->getAccess(), 'access ok');
    //compare access instead
    //$this->is($columnConfig->getIsInList(), $columnConfigMock->getIsInList(), 'isInList ok');
    $this->is_deeply($columnConfig->getRelation(), $columnConfigMock->getRelation(), 'relation ok');
    $this->is($columnConfig->getUnique(), $columnConfigMock->getUnique(), 'isInList ok');
    $this->is($columnConfig->getTranslated(), $columnConfigMock->getTranslated(), 'translation ok');
    $this->is($columnConfig->getDefaultValue(), $columnConfigMock->getDefaultValue(), 'default value ok');
  }
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'ull'));

$t = new myTestCase(115, new lime_output_color, $configuration);
$t->setMode('yml_fixtures');
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$app = Doctrine::getTable('UllFlowApp')->find(1);
$docs = Doctrine::getTable('UllFlowDoc')->findByUllFlowAppId(1);

$t->initialize();

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
  $t->isa_ok($columnsConfig, 'UllFlowDocColumnConfigCollection',
    'columnsConfig is an UllFlowDocColumnConfigCollection');
  
  $t->is(count($columnsConfig), 28, 'columnsConfig has the correct number of columns');
  
  //we really should take ordering into account here...
  $mocks = $t->getColumnsConfigMock();
  foreach($mocks as $mockKey => $mock)
  {
    $columnConfig = $columnsConfig[$mockKey];
    
    $t->isa_ok($columnConfig, 'ullColumnConfiguration', 'column configuration is correct class');
    $t->compareSingleColumnConfig($columnConfig, $mock);
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
  sfContext::getInstance()->getRequest()->setParameter('action', 'edit');
  $generator = new ullFlowGenerator($app);
  $generator->buildForm($doc);
  
  $t->loginAs('helpdesk_user');

  $generator->buildListOfUllFlowActionHandlers();
  
  $t->is(count($generator->getForm()->getFormFieldSchema()), 13, 'The form now contains one more field from the action handler');

  
$t->diag('getListOfUllFlowActionHandlers()');

  $handlers = $generator->getListOfUllFlowActionHandlers();
  $t->is(count($handlers), 2, 'Two UllFlowActionHandler were set');
  $t->isa_ok($handlers['assign_to_user'], 'ullFlowActionHandlerAssignToUser', 'The first field handler returns the correct object');
  $t->isa_ok($handlers['close'], 'ullFlowActionHandlerClose', 'The second field handler returns the correct object');
  
$t->diag('setUllFlowActionHandler()');

  $doc = Doctrine::getTable('UllFlowDoc')->find(2);
  $generator = new ullFlowGenerator($app, 'w');
  $generator->buildForm($doc);
  
  $generator->buildListOfUllFlowActionHandlers();
  
  $generator->setUllFlowActionHandler('assign_to_user');
  $form = $generator->getForm();  
  
$t->diag('getUllFlowActionHandler()');

  $t->isa_ok($generator->getUllFlowActionHandler(), 'ullFlowActionHandlerAssignToUser', 'Returns the correct object');  
  