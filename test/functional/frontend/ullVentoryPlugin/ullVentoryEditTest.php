<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllVentoryList();
$dgsEdit = $b->getDgsUllVentoryEdit();

$b
  ->info('Inventory create')
  ->get('ullVentory/create')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'create')
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option + option', 'Notebook')
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option + option', 'Apple')  
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option + option', 'MacBook')
  ->click('Save and close')
;
$b
  ->info('Submit without entering anything -> check for validation errors')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement($dgsEdit->get('type', 'error') . ' > ul > li', 'Required.')
  ->checkResponseElement($dgsEdit->get('manufacturer', 'error') . ' > ul > li', 'Please select a value or enter a new one')
  ->checkResponseElement($dgsEdit->get('model', 'error') . ' > ul > li', 'Please select a value or enter a new one')
;
  
$b
  ->info('Fill in values and submit')  
  ->click('Save and close', array('fields' => array(
    'ull_ventory_item_type_id'          => Doctrine::getTable('UllVentoryItemType')->findOneBySlug('printer')->id,
    'ull_ventory_item_manufacturer_id'  => Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Brother')->id,
    // test if "create input text field" overrides the "select box":
    'ull_ventory_item_model_id'         => Doctrine::getTable('UllVentoryItemModel')->findOneByName('MFC-440CN')->id,
    'ull_ventory_item_model_id_create'  => 'MFC-9840CDW',
    'serial_number'                     => 'abc123',
    'comment'                           => 'Permanent paper-jam!',
    'ull_user_id'                       => Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id,
    'ull_location_id'                   => Doctrine::getTable('UllLocation')->findOneByName('Wien Mollardgasse')->id,
  )))
;
  
$b
  ->info('Check created entry in list mode')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')  
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsList->get(1, 'model'), 'MFC-9840CDW')
  ->checkResponseElement($dgsList->get(2, 'model'), 'MacBook')  
  ->checkResponseElement($dgsList->get(3, 'model'), 'MFC-440CN')  
;  

$b
  ->info('Check created entry in edit mode')
  ->click('Edit', array(), array('position' => 1))
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option + option', 'Notebook')
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option[selected="selected"]', 'Printer')
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option + option', 'Apple')  
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option[selected="selected"]', 'Brother')
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option + option', 'MFC-440CN')
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option[selected="selected"]', 'MFC-9840CDW')
  ->checkResponseElement($dgsEdit->get('serial_number', 'value') . ' > input[value="abc123"]', true)
  
//  ->with('form')->debug()
//  ->with('form')->begin()->
//    hasErrors(false)->
//  end()
  
;