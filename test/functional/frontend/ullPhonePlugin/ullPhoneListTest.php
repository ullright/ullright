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
    ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows (4 users, 1 hidden)
    ->checkResponseElement($dgsList->get(1, 'name'), 'Admin Master')
    ->checkResponseElement($dgsList->get(1, 'phone_extension'), '1111')
    ->checkResponseElement($dgsList->get(1, 'mobile_number'), '')
    ->checkResponseElement($dgsList->get(1, 'location'), 'Wien Mollardgasse')
    ->checkResponseElement($dgsList->get(1, 'email_link') . ' > a[href="mailto:admin@example.com"]')
    ->checkResponseElement($dgsList->get(2, 'name'), 'Bauer Jack')
    ->checkResponseElement($dgsList->get(2, 'phone_extension'), '')
    ->checkResponseElement($dgsList->get(2, 'mobile_number'), '01 789987')
    ->checkResponseElement($dgsList->get(2, 'email_link') . ' > a[href="mailto:jack.bauer@example.com"]')
    ->checkResponseElement($dgsList->get(3, 'name'), 'User Test')
    ->checkResponseElement($dgsList->get(3, 'phone_extension'), '8888')
    ->checkResponseElement($dgsList->get(3, 'location'), 'New York 5th Ave')
    ->checkResponseElement($dgsList->get(3, 'email_link') . ' > a[href="mailto:test.user@example.com"]')
  ;
  
  //we want almeida visible for the next test
  $tonyAlmeida = Doctrine::getTable('UllUser')->findOneByUsername('tony_almeida');
  $tonyAlmeida->is_show_in_phonebook = true;
  $tonyAlmeida->phone_extension = 1234;
  $tonyAlmeida->save();
  
  $b
    ->info('Phone directory list with location headers')
    ->get('ullPhone/list/locationView/true')
    ->isStatusCode(200)
    ->isRequestParameter('module', 'ullPhone')
    ->isRequestParameter('action', 'list')
    ->isRequestParameter('locationView', 'true')
    ->responseContains('Phone directory')
    ->responseContains('Result list')
    
    //With location headers there are now 7 rows instead of 3
    ->checkResponseElement($dgsList->getFullRowSelector(), 7) // number of rows
    ->checkResponseElement($dgsListLocationHeader->get(1, 'location_name'), 'No location specified')
    ->checkResponseElement($dgsList->get(2, 'name'), 'Almeida Tony')
    ->checkResponseElement($dgsList->get(2, 'phone_extension'), '1234')
    ->checkResponseElement($dgsList->get(3, 'name'), 'Bauer Jack')
    ->checkResponseElement($dgsList->get(3, 'phone_extension'), '')
    ->checkResponseElement($dgsListLocationHeader->get(4, 'location_name'), 'New York 5th Ave (NYC)  Map')
    ->checkResponseElement($dgsList->get(5, 'name'), 'User Test')
    ->checkResponseElement($dgsList->get(5, 'phone_extension'), '8888')
    ->checkResponseElement($dgsList->get(5, 'email_link') . ' > a[href="mailto:test.user@example.com"]')
    ->checkResponseElement($dgsListLocationHeader->get(6, 'location_name'), 'Wien Mollardgasse (WMO)  Map')
    ->checkResponseElement($dgsList->get(7, 'name'), 'Admin Master')
    ->checkResponseElement($dgsList->get(7, 'phone_extension'), '1111')
    ->checkResponseElement($dgsList->get(7, 'mobile_number'), '')
  ;
