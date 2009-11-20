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
    ->info('Telephone directory list')
    ->get('ullPhone/list')
    ->loginAsAdmin()
    ->isStatusCode(200)
    ->isRequestParameter('module', 'ullPhone')
    ->isRequestParameter('action', 'list')
    ->responseContains('Phone directory')
    ->responseContains('Result list')
    
    //checks for
    //  a) correct content
    //  b) hidden phone/mobile extensions
    //  c) email links
    //  d) ordering (last name)
    ->checkResponseElement($dgsList->getFullRowSelector(), 4) // number of rows
    ->checkResponseElement($dgsList->get(1, 'name'), 'Admin Master')
    ->checkResponseElement($dgsList->get(1, 'phone_extension'), '1111')
    ->checkResponseElement($dgsList->get(1, 'mobile_number'), '-')
    ->checkResponseElement($dgsList->get(1, 'location'), 'Wien Mollardgasse')
    ->checkResponseElement($dgsList->get(1, 'email_link') . ' > a[href="mailto:admin@example.com"]')
    ->checkResponseElement($dgsList->get(2, 'name'), 'Admin User Helpdesk')
    ->checkResponseElement($dgsList->get(2, 'phone_extension'), '')
    ->checkResponseElement($dgsList->get(3, 'name'), 'User Helpdesk')
    ->checkResponseElement($dgsList->get(4, 'phone_extension'), '-')
    ->checkResponseElement($dgsList->get(4, 'mobile_number'), '+43 1 55556666')
    ->checkResponseElement($dgsList->get(4, 'location'), 'New York 5th Ave')
    ->checkResponseElement($dgsList->get(4, 'email_link') . ' > a[href="mailto:test.user@example.com"]')
  ;
  
  $b
    ->info('Phone directory list with location headers')
    ->get('ullPhone/list/locationView/true')
    ->isStatusCode(200)
    ->isRequestParameter('module', 'ullPhone')
    ->isRequestParameter('action', 'list')
    ->isRequestParameter('locationView', 'true')
    ->responseContains('Phone directory')
    ->responseContains('Result list')
    
    //With location headers there are now 7 rows instead of 4
    ->checkResponseElement($dgsList->getFullRowSelector(), 7) // number of rows
    ->checkResponseElement($dgsListLocationHeader->get(1, 'location_name'), 'No location specified')
    ->checkResponseElement($dgsList->get(2, 'name'), 'Admin User Helpdesk')
    ->checkResponseElement($dgsList->get(2, 'phone_extension'), '')
    ->checkResponseElement($dgsList->get(3, 'name'), 'User Helpdesk')
    ->checkResponseElement($dgsListLocationHeader->get(4, 'location_name'), 'New York 5th Ave (NYC)   Map')
    ->checkResponseElement($dgsList->get(5, 'phone_extension'), '-')
    ->checkResponseElement($dgsList->get(5, 'mobile_number'), '+43 1 55556666')
    ->checkResponseElement($dgsListLocationHeader->get(6, 'location_name'), 'Wien Mollardgasse (WMO)   Map')
    ->checkResponseElement($dgsList->get(7, 'phone_extension'), '1111')
    ->checkResponseElement($dgsList->get(7, 'mobile_number'), '-')
  ;
