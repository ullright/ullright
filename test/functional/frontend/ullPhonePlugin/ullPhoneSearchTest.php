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
      //there should be two location headers and two users
      ->checkElement($dgsList->getFullRowSelector(), 4) // number of rows
      ->checkElement($dgsListLocationHeader->get(1, 'location_name'), 'No location specified')
      ->checkElement($dgsList->get(2, 'name'), 'Admin User Helpdesk')
      ->checkElement($dgsList->get(2, 'phone_extension'), '')
      ->checkElement($dgsListLocationHeader->get(3, 'location_name'), 'Wien Mollardgasse (WMO)   Map')
      ->checkElement($dgsList->get(4, 'name'), 'Admin Master')
      ->checkElement($dgsList->get(4, 'phone_extension'), '1111')
      ->checkElement($dgsList->get(4, 'mobile_number'), '-')
    ->end()
    
    
    
    ->info('Search for \'1111\'')
    ->info('Should list one user')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '1111'))
    
    ->with('response')->begin()
      //there should be two location headers and two users
      ->checkElement($dgsList->getFullRowSelector(), 2) // number of rows
      ->checkElement($dgsListLocationHeader->get(1, 'location_name'), 'Wien Mollardgasse (WMO)   Map')
      ->checkElement($dgsList->get(2, 'name'), 'Admin Master')
      ->checkElement($dgsList->get(2, 'phone_extension'), '1111')
      ->checkElement($dgsList->get(2, 'mobile_number'), '-')
    ->end()
    
    
    
    ->info('Search for \'2222\'')
    ->info('Should list no user, since the number is hidden')
    ->call('/ullPhone/list/locationView/true', 'POST',
      array('filter[search]' => '2222'))
    
    ->with('response')->begin()
      ->checkElement($dgsList->getFullRowSelector(), 0) // number of rows
    ->end()
    
    
    
//    ->info('Search for \'7777\'')
//    ->info('Should list no user, since that is the number of a clone user')
//    ->info('(Doesn\'t work atm, parentheses problem?)')
//    ->call('/ullPhone/list/locationView/true', 'POST',
//      array('filter[search]' => '7777'))
//    
//    ->with('response')->begin()
//      ->checkElement($dgsList->getFullRowSelector(), 0) // number of rows
//    ->end()
  ;
  