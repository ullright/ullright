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
  ->isRequestParameter('action', 'create')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'error') . ' > ul > li', 'Required.')
  ->checkResponseElement($dgsEdit->get('type', 'error') . ' > ul > li', 'Required.')
  ->checkResponseElement($dgsEdit->get('manufacturer', 'error') . ' > ul > li', 'Please select a value or enter a new one')
  ->checkResponseElement($dgsEdit->get('model', 'error') . ' > ul > li', 'Please select a value or enter a new one')
;
  
$b
  ->info('Fill in values and submit')  
  ->click('Save and close', array('fields' => array(
    'inventory_number'                  => '1703',
    'ull_ventory_item_type_id'          => Doctrine::getTable('UllVentoryItemType')->findOneBySlug('printer')->id,
    'ull_ventory_item_manufacturer_id'  => Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Brother')->id,
    // test if "create input text field" overrides the "select box":
    'ull_ventory_item_model_id'         => Doctrine::getTable('UllVentoryItemModel')->findOneByName('MFC-440CN')->id,
    'ull_ventory_item_model_id_create'  => 'MFC-9840CDW',
    'serial_number'                     => 'abc123',
    'comment'                           => 'Permanent paper-jam!',
    'ull_user_id'                       => Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id,
//    'ull_location_id'                   => Doctrine::getTable('UllLocation')->findOneByName('Wien Mollardgasse')->id,
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
  ->checkResponseElement($dgsList->get(1, 'inventory_number'), '1703')
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
  ->isRequestParameter('inventory_number', '1703')
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option + option', 'Notebook')
  ->checkResponseElement('#fields_ull_ventory_item_type_id > option[selected="selected"]', 'Printer')
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option + option', 'Apple')  
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option[selected="selected"]', 'Brother')
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option + option', 'MFC-440CN')
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option[selected="selected"]', 'MFC-9840CDW')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'value') . ' > input[value="1703"]', true)
  ->checkResponseElement($dgsEdit->get('serial_number', 'value') . ' > input[value="abc123"]', true)
  ->checkResponseElement('input[id="fields_id"][value="3"]', true)
;

$b
  ->info('Edit: change inventory_numberID')
  ->setField('fields[inventory_number]', '1704')
;  

$b
  ->info('Edit: check changed ID')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1704')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'value') . ' > input[value="1704"]', true)
  ->checkResponseElement('input[id="fields_id"][value="3"]', true)  
;

$b
  ->info('Create: try to create an entry with an existing inventory_number')
  ->get('ullVentory/create')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'create')
  ->click('Save and close', array('fields' => array(
    'inventory_number'                  => '1702',
    'ull_ventory_item_type_id'          => Doctrine::getTable('UllVentoryItemType')->findOneBySlug('notebook')->id,
    'ull_ventory_item_manufacturer_id'  => Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Apple')->id,
    'ull_ventory_item_model_id'         => Doctrine::getTable('UllVentoryItemModel')->findOneByName('MacBook')->id,
    'serial_number'                     => '0123456789',
    'ull_user_id'                       => Doctrine::getTable('UllUser')->findOneByUsername('admin')->id,
//    'ull_location_id'                   => Doctrine::getTable('UllLocation')->findOneByName('Wien Mollardgasse')->id,
  )))
;
  
$b
  ->info('Create with existing inventory_number: check for "duplicate validation error')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'create')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'error') . ' > ul > li', 'Duplicate. Please use another value.')
;

$b
  ->info('Edit: try to change the inventory_number to an existing one')
  ->get('ullVentory/edit/1702')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1702')
  ->setField('fields[inventory_number]', '1701')
  ->click('Save only')  
;

$b
  ->info('Edit: check for "duplicate validation error')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1702')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'error') . ' > ul > li', 'Duplicate. Please use another value.')
  ->checkResponseElement('input[id="fields_id"][value="2"]', true)
;

$b
  ->info('Edit: revert the duplicate and save. (This tests the unique validator if it handles unchanged values correctly)')
  ->setField('fields[inventory_number]', '1702')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1702')
  ->checkResponseElement('input[id="fields_id"][value="2"]', true)  
;  

$b
  ->info('Edit: change the id to a new one (tests foreign key handling of item_id)')
  ->setField('fields[inventory_number]', '12345')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '12345')
  ->checkResponseElement('input[id="fields_id"][value="2"]', true)
;