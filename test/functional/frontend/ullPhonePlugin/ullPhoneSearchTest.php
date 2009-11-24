<?php

  $app = 'frontend';
  include dirname(__FILE__) . '/../../../bootstrap/functional.php';
  
  $b = new ullTestBrowser(null, null, array('configuration' => $configuration));
  $path = dirname(__FILE__);
  $b->setFixturesPath($path);
  $b->resetDatabase();
  
  $dgsList = $b->getDgsUllPhoneList();
  $dgsListLocationHeader = $b->getDgsUllPhoneListLocationHeader();
  
  $b
    ->info('Log in')
    ->get('ullPhone/list')
    ->loginAsAdmin()
    ->isStatusCode(200)
  
    ->info('Search for \'admin\'')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => 'admin'))
    
    ->with('request')->begin()
      ->isParameter('module', 'ullPhone')
      ->isParameter('action', 'list')
      ->isParameter('locationView', 'true')
    ->end()
    
    ->with('response')->begin()
      //there should be one location headers and one user
      ->checkElement($dgsList->getFullRowSelector(), 2) // number of rows
      ->checkElement($dgsListLocationHeader->get(1, 'location_name'), 'Wien Mollardgasse (WMO)   Map')
      ->checkElement($dgsList->get(2, 'name'), 'Admin Master')
      ->checkElement($dgsList->get(2, 'phone_extension'), '1111')
      ->checkElement($dgsList->get(2, 'mobile_number'), '')
    ->end()
    
    
    ->info('Search for \'1111\', should list one user')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '1111'))
    
    ->with('response')->begin()
      //there should be two location headers and two users
      ->checkElement($dgsList->getFullRowSelector(), 2) // number of rows
      ->checkElement($dgsListLocationHeader->get(1, 'location_name'), 'Wien Mollardgasse (WMO)   Map')
      ->checkElement($dgsList->get(2, 'name'), 'Admin Master')
      ->checkElement($dgsList->get(2, 'phone_extension'), '1111')
      ->checkElement($dgsList->get(2, 'mobile_number'), '')
    ->end()
    
    
    ->info('Search for \'2222\', should list no user, since the number is hidden')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '2222'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 0) // number of rows
    ->end()
    
    
    ->info('Search for \'8888\', should list one user, since it is the alternative number')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '8888'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 2) // number of rows
      ->checkElement($dgsListLocationHeader->get(1, 'location_name'), 'New York 5th Ave (NYC)   Map')
      ->checkElement($dgsList->get(2, 'name'), 'User Test')
      ->checkElement($dgsList->get(2, 'phone_extension'), '8888')
    ->end()
    
    
    ->info('Search for \'5566\', should list one user, mobile number')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '5566'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 2) // number of rows
      ->checkElement($dgsList->get(2, 'name'), 'User Test')
      ->checkElement($dgsList->get(2, 'mobile_number'), '+43 1 55556666')
    ->end()
    
    
    ->info('Search for \'8877\', should list no user, mobile number hidden')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '8877'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 0) // number of rows
    ->end()
    
    
    ->info('Search for \'7777\', should list no user, since that is the number of a clone user')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '8877'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 0) // number of rows
    ->end()
    
    
    ->info('Search for \'tony\', should list no user, hidden')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => 'tony'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 0) // number of rows
    ->end()
  ;
  