<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsEdit            = $b->getDgsUllVentoryEdit();
$dgsEditSoftware    = $b->getDgsUllVentoryEditSoftware();

$b
  ->info('Inventory create notebooK and select a software license') // IT MUST BE "notebooK" with a captial "K" because of an error in lime which other wise thinks a test has passe sucessfully
  ->get('ullVentory/createWithType/notebook/entity/stored')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'notebook')
  ->checkResponseElement($dgsEditSoftware->getFullRowSelector(), 1) // num of software entries
  ->checkResponseElement($dgsEditSoftware->get(1, 'label') . ' > label', 'Add software') // num of software entries
  ->setField('fields[ull_ventory_item_manufacturer_id_create]', 'Lenovo')
  ->setField('fields[ull_ventory_item_model_id_create]', 'ThinkPad SL500')
  ->setField('fields[inventory_number]', '1234')
  ->setField('fields[attributes][1][value]', '14')
  ->setField('fields[add_software]', Doctrine::getTable('UllVentorySoftware')->findOneByName('Adobe Writer 6.0')->id)
  ->click('Add software')
;

$b
  ->info('Check added software')
  ->isRedirected()
  ->followRedirect() 
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement($dgsEditSoftware->getFullRowSelector(), 2) // num of software entries
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option', 3)
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option', '')
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option + option', 'XXXX-YYYY-1234 (1 of 1 used)')
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option + option + option', 'YYYY-ZZZZ-5678 (0 of 5 used)')
//  ->checkResponseElement($dgsEditSoftware->get(2, 'label') . ' > label', 'Add software') // num of software entries
  ->checkResponseElement('input[id="fields_software_0_enabled"][checked="checked"]', true)
  ->setField('fields[software][0][ull_ventory_software_license_id]', 1)
  ->setField('fields[software][0][comment]', 'Buy new license soon!')
  ->click('Save only')
;

$b
  ->info('Check license selection')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->checkResponseElement('input[id="fields_software_0_enabled"][checked="checked"]', true)
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option[selected="selected"]', 'XXXX-YYYY-1234 (2 of 1 used)')
  ->checkResponseElement('input[id="fields_software_0_comment"][value="Buy new license soon!"]', true)
  ->checkResponseElement('input[id="fields_software_1_enabled"][checked="checked"]', false)
;

$b
  ->info('Add another software and deselect the first one')
  ->setField('fields[software][0][enabled]', false)
  ->setField('fields[add_software]', Doctrine::getTable('UllVentorySoftware')->findOneByName('Microsoft Windows 7')->id)
  ->click('Add software')
;

$b
  ->info('Check modified selection')
  ->isRedirected()
  ->followRedirect() 
  ->isStatusCode(200)
  ->checkResponseElement($dgsEditSoftware->getFullRowSelector(), 2) // num of software entries
  ->checkResponseElement($dgsEditSoftware->get(1, 'label') . ' > label', 'Microsoft Windows 7')  
  ->checkResponseElement('input[id="fields_software_0_enabled"][checked="checked"]', true)
;