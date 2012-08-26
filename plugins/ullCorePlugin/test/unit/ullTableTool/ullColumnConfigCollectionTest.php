<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new myTestCase(89,  new lime_output_color, $configuration);

$t->diag('buildFor()');

  $c = ullColumnConfigCollection::buildFor('TestTable', null, 'edit');

  $t->isa_ok($c, 'TestTableColumnConfigCollection', 'Returns the correct object');
  $t->is($c->getModelName(), 'TestTable', 'Sets the correct model name');
  $t->is($c->getRequestAction(), 'edit', 'Sets the correct default action');
  $t->is($c instanceof ullGeneratorBase, true, 'Instance of ullGeneratorBase');
  $t->is($c instanceof ullColumnConfigCollection, true, 'Instance of ullColumnConfigCollection');
  $t->is($c instanceof ArrayAccess, true, 'Implements ArrayAccess');
  $t->is($c instanceof IteratorAggregate, true, 'Implements IteratorAggregat');
  $t->is($c instanceof Countable, true, 'Implements Countable');
  $t->isa_ok($c->getFirst(), 'ullColumnConfiguration', 'returns an array of ullColumnConfiguration objects');

$t->diag('buildFor() - createColumnConfigs');
  $t->is($c['my_string']->getTranslated(), true, 'sets translation flag');
  $t->is($c['my_text']->getTranslated(), true, 'sets translation flag');

$t->diag('buildFor() - applyCommonSettings');
  $t->is($c['my_email']->getColumnName(), 'my_email', 'sets column name');
  $t->is($c['my_email']->getModelName(), 'TestTable', 'sets model name');
  $t->is($c['my_email']->getColumnsConfigClass(), 'TestTableColumnConfigCollection', 'sets parent\'s column config class name');
  $t->is($c['my_email']->getAccess(), 'w', 'defaultAccess mode is set to "w" because of default action "edit"');
  $t->is($c['creator_user_id']->getAccess(), 'r', 'access is set to "r" for defined readOnly columns');
  $t->is($c['namespace']->getAccess(), null, 'blacklisted columns are disabled');
  $t->is($c->getLast()->getColumnName(), 'updated_at', 'sorts columns to be in correct sequence');

$t->diag('buildFor() - applyDoctrineSettings for "id"');
  $t->is($c['id']->getAccess(), 'r', 'access is set to "r" for id');
  $t->is($c['id']->getMetaWidgetClassName(), 'ullMetaWidgetInteger', 'sets the correct metaWidget');
  $t->is($c['id']->getValidatorOption('required'), true, 'sets the validator to "required" because of "notnull"');
  $t->is($c['id']->getUnique(), true, 'sets "unique" flag');
  $t->is($c['id']->getRelation(), false, 'no relation set');

$t->diag('buildFor() - applyDoctrineSettings for "my_email"');
  $t->is($c['my_email']->getMetaWidgetClassName(), 'ullMetaWidgetEmail', 'sets the correct metaWidget');
  $t->is($c['my_email']->getWidgetAttribute('maxlength'), '64', 'sets the correct widget length');
  $t->is($c['my_email']->getValidatorOption('max_length'), '64', 'sets the correct validator length');
  $t->is($c['my_email']->getValidatorOption('required'), true, 'sets the validator to "required" because of "notnull"');
  $t->is($c['my_email']->getUnique(), true, 'sets "unique" flag');
  
$t->diag('buildFor() - applyDoctrineSettings for "ull_user_id"');
  $t->is($c['ull_user_id']->getMetaWidgetClassName(), 'ullMetaWidgetUllEntity', 'sets the correct metaWidget');
  $t->is($c['ull_user_id']->getOption('entity_classes'), array('UllUser'), 'sets the correct options');
  $t->is($c['ull_user_id']->getRelation(), array('alias' => 'UllUser', 'model' => 'UllUser', 'foreign_id' => 'id'), 'returns the correct relation settings');
  
$t->diag('buildFor() - applyDoctrineSettings for sums');
  $t->is($c['id']->getCalculateSum(), false, 'Do not build sums for "id" column');  
  $t->is($c['ull_user_id']->getCalculateSum(), false, 'Do not build sums for columns ending with "id" column');
  $t->is($c['my_email']->getCalculateSum(), false, 'Do not build sums for non-numeric columns');
  $t->is($c['my_select_box']->getCalculateSum(), true, 'Build sums for numeric columns');
  
$t->diag('buildFor() - Label');  
  $t->is($c['my_email']->getLabel(), 'My email', 'returns the correct humanized label for a label not in humanizer dictionary');
  $t->is($c['creator_user_id']->getLabel(), 'Created by', 'returns the correct humanized label for a label listed in humanizer dictionary');
  sfContext::getInstance()->getUser()->setCulture('de');
  $cGerman = ullColumnConfigCollection::buildFor('TestTable');
  $t->is($cGerman['creator_user_id']->getLabel(), 'Erstellt von', 'returns the correct translated humanized label for a label listed in humanizer dictionary');

$t->diag('buildFor() - applyCustomSettings');
  $t->is($c['my_string']->getLabel(), 'My custom string label', 'applies custom label set in applyCustomColumnConfigSettings()');  
  

$t->diag('buildCollection()');
  $c = new TestTableColumnConfigCollection('TestTable');
  $t->isa_ok($c, 'TestTableColumnConfigCollection', 'Creates the correct type');
  $t->is(isset($c['my_email']), false, 'Not yet built');    
  $c->buildCollection();  
  $t->is($c['my_email']->getColumnsConfigClass(), 'TestTableColumnConfigCollection', 'sets parent\'s column config class name');
  
  
$columnConfig = new ullColumnConfiguration;
$columnConfig->setColumnName('my_email');  
  
$t->diag('offsetSet()');
  try
  {
    $c->offsetSet('my_email', 'foobar');
    $t->fail('Doesn\'t throw an exception when giving anything else than a ullColumnConfiguration object as value');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when giving anything else than a ullColumnConfiguration object as value');
  }
  $t->is($c->offsetSet('my_email', $columnConfig), null, 'Sets an offset');
  
  
$t->diag('offsetExists()');
  $t->is($c->offsetExists('my_email'), true, 'Returns true for an existing offset');  
  $t->is($c->offsetExists('blubb'), false, 'Returns false for an non-existing offset');

  
$t->diag('offsetGet()');
  $t->is($c->offsetGet('my_email')->getColumnName(), 'my_email', 'Returns the correct offset');
  try
  {
    $c->offsetGet('blubb');
    $t->fail('Doesn\'t throw an exception when trying to get an invalid offset');    
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when trying to get an invalid offset');
  }  
  
  
$t->diag('offsetUnset()');
  $t->is($c->offsetUnset('my_email'), null, 'Unsets an offset');
  $t->is($c->offsetExists('my_email'), false, 'Offset was really removed');
  try
  {
    $c->offsetUnset('my_email');
    $t->fail('Doesn\'t throw an exception when trying to unset an invalid offset');    
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when trying to unset an invalid offset');
  } 


$t->diag('Iterator');
  $c = new ullColumnConfigCollection('TestTable');
  $c['one'] = new UllColumnConfiguration;
  $c['one']->setColumnName('one');
  $k = null;
  $v = null;
  foreach($c as $key => $value)
  {
    $k = $key;
    $v = $value;
  }
  $t->is($k, 'one', 'Checking key - the collection is iterable');
  $t->is($v->getColumnName(), 'one', 'Checking value - the collection is iterable');  
    
  
$t->diag('order()');
  $c = new ullColumnConfigCollection('TestTable');
  $c['three'] = new UllColumnConfiguration; 
  $c['one'] = new UllColumnConfiguration;
  $c['one']->setColumnName('one');
  $c['two'] = new UllColumnConfiguration;
  
  $order = array('one', 'two');
  
  $c->order($order);
  
  $t->is($c->getKeys(), array('one', 'two', 'three'), 'Orders the collection correctly');
  
  
$t->diag('order() with sections');
  $c['four'] = new UllColumnConfiguration;

  $order = array(
    'section_1' => array(
      'four',
      'three',
    ),
    'section_2' => array(
      'two',
    )
  );
  $c->order($order);
  
  $t->is($c->getKeys(), array('four', 'three', 'two', 'one'), 'Orders the collection correctly');
  $t->is($c['four']->getSection(), 'section_1', 'Returns the correct section');
  $t->is($c['three']->getSection(), 'section_1', 'Returns the correct section');
  $t->is($c['two']->getSection(), 'section_2', 'Returns the correct section');
  $t->is($c['one']->getSection(), '', 'Returns the correct section');
  
  unset($c['four']);
  
  
$t->diag('order() removes section when none given');
  $order = array(
    'one',
    'two',
  );
  $c->order($order);
  $t->is($c->getKeys(), array('one', 'two', 'three'), 'Orders the collection correctly');
  $t->is($c['one']->getSection(), null, 'Correctly removes the section');
  $t->is($c['two']->getSection(), null, 'Correctly removes the section');
  $t->is($c['three']->getSection(), 'section_1', 'Correctly leaves a unspecified element unchanged');
  
  

$t->diag('Countable');
  $t->is(count($c), 3 , 'count() returns the correct number');  
  
$t->diag('orderBottom()');
  $c = new ullColumnConfigCollection('TestTable');
  $c['three'] = new UllColumnConfiguration; 
  $c['one'] = new UllColumnConfiguration;
  $c['two'] = new UllColumnConfiguration;    

  $order = array('two', 'three');
  
  $c->orderBottom($order);
  
  $t->is($c->getKeys(), array('one', 'two', 'three'), 'Orders the collection correctly');
  
$t->diag('disable()');
  $c->disable(array('three'));
  $t->is($c['three']->getAccess(), false, 'Sets access to null');
  
$t->diag('create()');
  $c->create('four');
  $t->is($c['four']->getColumnName(), 'four', 'Creates a new columnConfig correctly');  
  
$t->diag('getActiveColumns');
  $t->is(array_keys($c->getActiveColumns()), array('one', 'two', 'four'), 'returns the correct columns');  
  
$t->diag('getAutoRenderedColumns');
  $c['two']->setAutoRender(false);
  $t->is(array_keys($c->getAutoRenderedColumns()), array('one', 'four'), 'returns the correct columns');  
  
$t->diag('getDatabaseColumns');
  $c['one']->setIsArtificial(true);
  $t->is(array_keys($c->getDatabaseColumns()), array('two', 'four'), 'returns the correct columns');  

$t->diag('getUnsortableColumns');
  $c['three']->setIsSortable(false);
  $t->is(array_keys($c->getUnsortableColumns()), array('three'), 'returns the correct columns');  
 
$t->diag('setIsRequired()');  
  $c = new ullColumnConfigCollection('TestTable');
  $c['one'] = new UllColumnConfiguration;
  $c['two'] = new UllColumnConfiguration;
  $c['three'] = new UllColumnConfiguration; 
  $c->setIsRequired(array('one', 'three'));
  $t->is($c['one']->getIsRequired(), true, 'Returns true for a required field'); 
  $t->is($c['two']->getIsRequired(), false, 'Returns false for a non-required field');
  $t->is($c['three']->getIsRequired(), true, 'Returns true for a required field');
  
$t->diag('getTableConfig()');
  $c = ullColumnConfigCollection::buildFor('UllJobTitle');
  $tableConfig = $c->getTableConfig();
  $t->isa_ok($tableConfig, 'UllJobTitleTableConfiguration', 'Returns a table config');
  $t->is($tableConfig->getToStringColumn(), 'name', 'Uses the correct class'); 
  
$t->diag('useManyToManyRelation()');
  $c = ullColumnConfigCollection::buildFor('UllPermission');
  $c->useManyToManyRelation('UllGroup');
  $t->ok($c->offsetExists('UllGroup'), 'Generated a new field');
  $cc = $c->offsetGet('UllGroup');
  $t->is($cc->getMetaWidgetClassName(), 'ullMetaWidgetManyToMany', 'Returns the correct meta widget');
  $t->is($cc->getWidgetOption('model'), 'UllGroup', 'Returns the correct widget option "model"');
  $q = $cc->getWidgetOption('query');
  $t->isa_ok($q, 'ullQuery', 'Returns a ull query to support translated columns');
  $t->is(
    $q->getSqlQuery(), 
    "SELECT u.id AS u__id, u.type AS u__type, u.display_name AS u__display_name FROM ull_entity u WHERE (u.is_active = ? AND (u.type = 'group')) ORDER BY u.display_name asc", 
    'Returns the correct query'
  );
  $t->is($cc->getWidgetOption('key_method'), 'id', 'Returns the corret widget option "key_method"');
  $t->is($cc->getWidgetOption('method'),  'display_name', 'Returns the corret widget option "method"'); 
  $t->is($cc->getValidatorOption('model'), 'UllGroup', 'Returns the correct validator option "model"');
  $t->is($cc->getValidatorOption('query'), $q, 'Returns the correct validator option "q"');
  
$t->diag('useManyToManyRelation() using model override');
  $c = ullColumnConfigCollection::buildFor('UllPermission');
  $c->useManyToManyRelation('UllGroup', 'UllEntity');
  $cc = $c->offsetGet('UllGroup');
  $t->is($cc->getWidgetOption('model'), 'UllEntity', 'Returns the correct overridden model');
  
$t->diag('markAsAdvancedFields()');
  $c = ullColumnConfigCollection::buildFor('TestTable');
  $c['my_string']->setWidgetAttribute('class', 'foo');
  $t->is($c['my_string']->getWidgetAttribute('class'), 'foo', 'No "advanced form field" class set by default');
  $c->markAsAdvancedFields(array('my_text', 'my_string'));
  $t->is($c['my_string']->getWidgetAttribute('class'), 'foo advanced_form_field', 'Attribute class="advanced_form_field" correctly set');
  
$t->diag('remove()');
  $c->remove(array('my_useless_column'));
  $t->is(in_array('my_useless_column', $c->getKeys()), false, 'Removes column');
  