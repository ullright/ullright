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
      array('autocomplete_sidebarPhoneSearch' => 'admin'))
    
    ->with('request')->begin()
      ->isParameter('module', 'ullPhone')
      ->isParameter('action', 'list')
      ->isParameter('locationView', 'true')
    ->end()
    
    ->with('response')->begin()
      //there should be two location headers and two users
      ->checkElement($dgsList->getFullRowSelector(), 4) // number of rows
      ->checkElement($dgsListLocationHeader->get(1, 'location_name'), 'No location specified')
      ->checkElement($dgsList->get(2, 'last_name'), 'Admin User')
      ->checkElement($dgsList->get(2, 'phone_extension'), '')
      ->checkElement($dgsListLocationHeader->get(3, 'location_name'), 'Wien Mollardgasse (WMO) Map')
      ->checkElement($dgsList->get(4, 'last_name'), 'Admin')
      ->checkElement($dgsList->get(4, 'phone_extension'), '1111')
      ->checkElement($dgsList->get(4, 'fax_extension'), '-')
    ->end()
  ;
  