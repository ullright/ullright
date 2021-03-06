<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList            = $b->getDgsUllVentoryList();
$dgsEdit            = $b->getDgsUllVentoryEdit();
$dgsEditAttributes  = $b->getDgsUllVentoryEditAttributes();
$dgsOrigin          = $b->getDgsUllVentoryOrigin();
$dgsOwner           = $b->getDgsUllVentoryOwner();
$dgsMemory          = $b->getDgsUllVentoryMemory();

$b
  ->info('Inventory create - type selection -> leave empty to provoke validation error')
  ->get('ullVentory/create')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('type', '')
  ->checkResponseElement($dgsEdit->getFullRowSelector(), 1) // num of rows
  ->click('Apply')
;

$b
  ->info('Inventory create - select type')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('type', '')
  ->isRequestParameter('entity', 'stored')
  ->checkResponseElement($dgsEdit->get('type', 'error') . ' > ul > li', 'Required.')
  ->setField('fields[type]', 'printer')
  ->click('Apply')
;
  

$b
  ->info('Inventory create')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'printer') 
  ->isRequestParameter('entity', 'stored') 
  // item properties
  ->checkResponseElement($dgsEdit->getFullRowSelector(), 6) // num of rows
  ->checkResponseElement($dgsEdit->get('type', 'value'), 'Printer')
  ->checkResponseElement($dgsEdit->get('type', 'value') . ' input[type="hidden"]', true)
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option', 2)
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option + option', 'Brother')  
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option', 2)
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option + option', 'MFC-440CN')
  ->checkResponseElement('input[id="fields_ull_entity_id"][type="hidden"][value="'. Doctrine::getTable('UllVentoryStatusDummyUser')->findOneByUsername('stored')->id . '"]')
  // attributes
  ->checkResponseElement($dgsEditAttributes->getFullRowSelector(), 3)
  // memory
  ->checkResponseElement($dgsOwner->getFullRowSelector(), 3)
  ->click('Save and return to list')
;

$b
  ->info('Submit without entering anything -> check for validation errors')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'printer')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'error') . ' > ul > li', 'Required.')
  ->checkResponseElement($dgsEdit->get('manufacturer', 'error') . ' > ul > li', 'Please select a value or enter a new one')
  ->checkResponseElement($dgsEdit->get('model', 'error') . ' > ul > li', 'Please select a value or enter a new one')
  ->checkResponseElement('input[id="fields_ull_entity_id"][type="hidden"][value="'. Doctrine::getTable('UllVentoryStatusDummyUser')->findOneByUsername('stored')->id . '"]')  
;
  
$b
  ->info('Fill in values and submit')  
  ->setField('fields[inventory_number]', '1703')
  ->setField('fields[ull_ventory_item_manufacturer_id]', Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Brother')->id)
  // test if "create input text field" overrides the "select box":
  ->setField('fields[ull_ventory_item_model_id]', Doctrine::getTable('UllVentoryItemModel')->findOneByName('MFC-440CN')->id)
  ->setField('fields[ull_ventory_item_model_id_create]', 'MFC-9840CDW')
  ->setField('fields[serial_number]', 'abc123')
  ->setField('fields[comment]', 'Permanent paper-jam!')
  
  //attributes
  ->setField('fields[attributes][0][value]', 'Laser')
  ->setField('fields[attributes][0][comment]', 'Single pass color')
  ->setField('fields[attributes][1][value]', '10')
  ->setField('fields[attributes][1][comment]', 'Old and slow')
  
  ->click('Save and return to list')  
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
  ->checkResponseElement($dgsList->get(1, 'owner'), 'Stored')
  ->checkResponseElement($dgsList->get(2, 'model'), 'MFC-440CN')
  ->checkResponseElement($dgsList->get(3, 'model'), 'MacBook')
;  

$b
  ->info('Check created entry in edit mode')
  ->click('Edit', array(), array('position' => 1))
  
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1703')
  ->checkResponseElement('div.edit_container h3', 'Item of user: Stored')
  ->checkResponseElement($dgsEdit->get('type', 'value'), 'Printer')
  ->checkResponseElement($dgsEdit->get('type', 'value') . ' input[type="hidden"]', true)  
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option + option', 'Apple')  
  ->checkResponseElement('#fields_ull_ventory_item_manufacturer_id > option[selected="selected"]', 'Brother')
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option', 3)
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option + option', 'MFC-440CN')
  ->checkResponseElement('#fields_ull_ventory_item_model_id > option[selected="selected"]', 'MFC-9840CDW')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'value') . ' > input[value="1703"]', true)
  ->checkResponseElement($dgsEdit->get('serial_number', 'value') . ' > input[value="abc123"]', true)
  ->checkResponseElement('input[id="fields_id"][value="3"]', true)
  //attributes
  ->checkResponseElement('input[id="fields_attributes_0_value"][value="Laser"]', true)
  ->checkResponseElement('input[id="fields_attributes_0_comment"][value="Single pass color"]', true)
  ->checkResponseElement('input[id="fields_attributes_1_value"][value="10"]', true)
  ->checkResponseElement('input[id="fields_attributes_1_comment"][value="Old and slow"]', true)
  // owner
  ->checkResponseElement($dgsOwner->getFullRowSelector(), 2)
  ->checkResponseElement('#fields_memory_target_ull_entity_id > option[selected="selected"]', 'Stored')  
  //memory
  ->checkResponseElement($dgsMemory->getFullRowSelector(), 2)
//  ->dumpDie()
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
  ->info('Edit: change owner')
  ->setField('fields[memory][target_ull_entity_id]', Doctrine::getTable('UllUser')->findOneByUsername('admin')->id)
  ->setField('fields[memory][comment]', 'The evil master admin want\'s everything!')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1704')
  ->checkResponseElement('div.edit_container h3', 'Item of user: Master Admin')
  // owner
  ->checkResponseElement('#fields_memory_target_ull_entity_id > option[selected="selected"]', 'Admin Master')  
  //memory
  ->checkResponseElement($dgsMemory->getFullRowSelector(), 3)
;

$b
  ->info('Edit: owner: don\'t change anything -> no memory should be created')
  ->setField('fields[comment]', 'Paper-jam again!')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1704')
  ->checkResponseElement('div.edit_container h3', 'Item of user: Master Admin')
  ->checkResponseElement('input[id="fields_comment"][value="Paper-jam again!"]', true)
  // owner
  ->checkResponseElement('#fields_memory_target_ull_entity_id > option[selected="selected"]', 'Admin Master')  
  //memory
  ->checkResponseElement($dgsMemory->getFullRowSelector(), 3)
;

$b
  ->info('Edit: owner: set only comment but no new owner')
  ->setField('fields[memory][comment]', 'Replaced paper tray')
  ->click('Save only')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1704')
  ->checkResponseElement('div.edit_container h3', 'Item of user: Master Admin')
  // owner
  ->checkResponseElement('#fields_memory_target_ull_entity_id > option[selected="selected"]', 'Admin Master')  
  //memory
  ->checkResponseElement($dgsMemory->getFullRowSelector(), 4)
;

$b
  ->info('Create: try to create an entry with an existing inventory_number')
  ->get('ullVentory/createWithType/notebook')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->click('Save and return to list', array('fields' => array(
    'inventory_number'                  => '1702',
    'ull_ventory_item_type_id'          => Doctrine::getTable('UllVentoryItemType')->findOneBySlug('notebook')->id,
    'ull_ventory_item_manufacturer_id'  => Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Apple')->id,
    'ull_ventory_item_model_id'         => Doctrine::getTable('UllVentoryItemModel')->findOneByName('MacBook')->id,
    'serial_number'                     => '0123456789',
//    'ull_location_id'                   => Doctrine::getTable('UllLocation')->findOneByName('Wien Mollardgasse')->id,
  )))
;
  
$b
  ->info('Create with existing inventory_number: check for "duplicate validation error')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->checkResponseElement($dgsEdit->get('inventory_number', 'error') . ' > ul > li', 'Duplicate. Please use another value.')
;

$b
  ->info('Edit: change attributes (invalid)')
  ->get('ullVentory/edit/1701')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1701')
  //->dumpDie()
  ->setField('fields[attributes][1][value]', '18foo')
  ->setField('fields[attributes][1][comment]', 'blabla')
  ->setField('fields[attributes][2][value]', '100')
  ->click('Save only')  
;

$b
  ->info('Edit: change attributes check validation errors')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1701')
  ->checkResponseElement($dgsEditAttributes->get(2, 'value') . ' ul > li', 'Invalid.')
  ->checkResponseElement($dgsEditAttributes->get(2, 'comment') . ' > input[value="blabla"]', true)
  ->checkResponseElement($dgsEditAttributes->get(3, 'value'). ' > input[value="100"]', true)
  ->setField('fields[attributes][1][value]', '18')
  ->click('Save only')  
;

$b
  ->info('Edit: check saved attributes')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('inventory_number', '1701')
  ->checkResponseElement($dgsEditAttributes->get(2, 'value'). ' > input[value="18"]', true)
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

$b
  ->info('Create: select model and load preset')
  ->get('ullVentory/createWithType/notebook')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'notebook')
  ->setField('fields[inventory_number]', '1705')
  ->setField('fields[ull_ventory_item_manufacturer_id]', Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Apple')->id)
  ->setField('fields[ull_ventory_item_model_id]', Doctrine::getTable('UllVentoryItemModel')->findOneByName('Macbook')->id)
  ->checkResponseElement('input[id="fields_attributes_0_value"][value=""]', true)
  ->click('Load presets')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'notebook')
  ->checkResponseElement('input[id="fields_attributes_1_value"][value="13"]', true)
;

$b
  ->info('Create: save preset')
  ->setField('fields[attributes][1][value]', '17') // overwrite an existing preset
  ->setField('fields[attributes][2][value]', '1000') // save a new preset
  ->setField('fields[attributes][0][value]', '2009-07-28') // set a preset that is not presetable
  ->setField('fields[save_preset]', true) 
  ->click('Save and return to list')
  ->isRedirected()
  ->followRedirect()
;

$b
  ->info('Create: check saved preset')
  ->get('ullVentory/createWithType/notebook')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'notebook')
  ->setField('fields[inventory_number]', '1706')
  ->setField('fields[ull_ventory_item_manufacturer_id]', Doctrine::getTable('UllVentoryItemManufacturer')->findOneByName('Apple')->id)
  ->setField('fields[ull_ventory_item_model_id]', Doctrine::getTable('UllVentoryItemModel')->findOneByName('Macbook')->id)
  ->checkResponseElement('input[id="fields_attributes_0_value"][value=""]', true)
  ->click('Load presets')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'notebook')
  ->checkResponseElement('input[id="fields_attributes_1_value"][value="17"]', true)
  ->checkResponseElement('input[id="fields_attributes_2_value"][value="1000"]', true)
  ->checkResponseElement('input[id="fields_attributes_0_value"][value=""]', true)
;

