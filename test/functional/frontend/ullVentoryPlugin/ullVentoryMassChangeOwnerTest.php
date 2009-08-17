<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$browser = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$browser->setFixturesPath($path);
$browser->resetDatabase();


$newItem = new UllVentoryItem();
$newItem->ull_entity_id = Doctrine::getTable('UllUser')->findOneByUsername('admin')->id;
$newItem->inventory_number = '0815';
$newItem->ull_ventory_item_model_id = Doctrine::getTable('UllVentoryItemModel')->findOneByName('MFC-440CN')->id;
$newItem->updated_at = '2000-01-01';
$newItem->save();

$testUserId = Doctrine::getTable('UllUser')->findOneByUsername('test_user')->id;
$stolenDummyUserId = Doctrine::getTable('UllVentoryStatusDummyUser')->findOneByUsername('stolen')->id;
$dgsList = $browser->getDgsUllVentoryList();

$browser
  ->info('Mass change owner - navigating to list')
  ->get('ullVentory/list')
  ->loginAsAdmin()
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullVentory')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3)
  ->checkResponseElement($dgsList->get(1, 'owner'), 'Test User')
  ->checkResponseElement($dgsList->get(2, 'owner'), 'Test User')
  ->checkResponseElement($dgsList->get(3, 'owner'), 'Master Admin')
;

$browser
  ->info('Mass change owner - selecting test user')
  ->with('response')->begin()
    ->contains('Result list')
    ->click('Result list', array())
  ->end()
  ->call('/ullVentory/list/filter[ull_entity_id]/' . $testUserId, 'get', array()) 
;

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
  ->checkResponseElement($dgsList->getFullRowSelector(), 2)
;

$browser
  ->with('response')->begin()
    ->contains('Change owner')
    ->click('Change owner', array())
  ->end()
;

$browser
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'massChangeOwner')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
;

$browser
  ->info('Mass change owner - provoking validation error')
  ->call('/ullVentory/massChangeOwner/oldEntityId/' . $testUserId, 'POST', array (
  'fields_ull_new_owner_entity_id_filter' => '',
  'fields' => 
  array (
    'ull_new_owner_entity_id' => '',
    'ull_change_comment' => '',
  ),
  'commit' => 'Change owner',
  'oldEntityId' => $testUserId,
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'massChangeOwner')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->contains('Please correct the following errors')
    ->contains('Required')
  ->end()
;

$browser
  ->info('Mass change owner - submitting new owner: stolen')
  ->call('/ullVentory/massChangeOwner/oldEntityId/' . $testUserId, 'POST', array (
  'fields_ull_new_owner_entity_id_filter' => '',
  'fields' => 
  array (
    'ull_new_owner_entity_id' => $stolenDummyUserId,
    'ull_change_comment' => 'Remote wipe',
  ),
  'commit' => 'Change owner',
  'oldEntityId' => $testUserId,
))
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'massChangeOwner')
  ->end()
;
$browser
  ->with('response')->begin()
    ->isRedirected(1)
    ->isStatusCode(302)
  ->end()
  ->followRedirect()
;

$browser
  ->info('Mass change owner - validating new owner')
  ->with('request')->begin()
    ->isParameter('module', 'ullVentory')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
  ->checkResponseElement($dgsList->getFullRowSelector(), 3)
  ->checkResponseElement($dgsList->get(1, 'owner'), 'Stolen')
  ->checkResponseElement($dgsList->get(2, 'owner'), 'Stolen')
  ->checkResponseElement($dgsList->get(3, 'owner'), 'Master Admin')
;

