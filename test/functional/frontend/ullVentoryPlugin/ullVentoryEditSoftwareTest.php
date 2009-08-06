<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsEdit            = $b->getDgsUllVentoryEdit();
$dgsEditSoftware    = $b->getDgsUllVentoryEditSoftware();

$b
  ->info('Inventory create notebooK and select a software license')
  ->get('ullVentory/createWithType/notebook/entity/stored')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'createWithType')
  ->isRequestParameter('type', 'notebook')
  ->checkResponseElement($dgsEditSoftware->getFullRowSelector(), 2) // num of software entries
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option', 3)
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option', '')
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option + option', 'XXXX-YYYY-1234 (1 of 1 used)')
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option + option + option', 'YYYY-ZZZZ-5678 (0 of 5 used)')
  ->setField('fields[ull_ventory_item_manufacturer_id_create]', 'Lenovo')
  ->setField('fields[ull_ventory_item_model_id_create]', 'ThinkPad SL500')
  ->setField('fields[inventory_number]', '1234')
  ->setField('fields[attributes][0][value]', '14')
  ->setField('fields[software][0][enabled]', true)
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
  ->info('Modify license data')
  ->setField('fields[software][0][enabled]', false)
  ->setField('fields[software][1][enabled]', true)
  ->setField('fields[software][1][comment]', 'Is seven a good number?')
  ->click('Save only')
;

$b
  ->info('Check modified selection')
  ->isRedirected()
  ->followRedirect() 
  ->isStatusCode(200) 
  ->checkResponseElement('input[id="fields_software_0_enabled"][checked="checked"]', false)
  ->checkResponseElement('select#fields_software_0_ull_ventory_software_license_id > option[selected="selected"]', '')
  ->checkResponseElement('input[id="fields_software_0_comment"][value=""]', true)  
  ->checkResponseElement('input[id="fields_software_1_enabled"][checked="checked"]', true)
  ->checkResponseElement('input[id="fields_software_1_comment"][value="Is seven a good number?"]', true)
;