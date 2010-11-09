<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllFlowListGeneric();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();
$dgsEditTT = $b->getDgsUllFlowEditTroubleTicket();

$b
  ->diag('ullFlow Home')
  ->get('ullAdmin/index')
  ->loginAsTestUser()
  ->get('ullFlow/index')
;

$b
  ->diag('list access rights as test user')
  ->click('All entries')
;

$b
  ->diag('list - content')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) //number of rows
  // read access because user is member of TestGroup
  ->checkResponseElement($dgsList->get(1, 'subject'), 'AAA My second thing todo')
  // read access because doc is assigned to the user
  ->checkResponseElement($dgsList->get(2, 'subject'), 'My first thing todo')
  // read access because the doc was once (=UllFlowMemory) assigned to TestGroup, which the user is member of
  ->checkResponseElement($dgsList->get(3, 'subject'), 'My first trouble ticket')
;

$b
  ->diag('list - selecting one application')
  ->get('ullFlow/list/app/trouble_ticket')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 1) //number of rows
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'My first trouble ticket')  
;

$b
  ->diag('edit - test access for a document with only read access')
  ->click('Edit')
  ->checkResponseElement($dgsEditTT->get('subject', 'value'), 'My first trouble ticket')
  // check workflow access
  // test that there is nothing but whitespace inside the tag (\w = any word character; ! = not)
  // useless at the moment because there is no access to the actions at all
//  ->checkResponseElement('div.action_buttons_edit_left', '!/\w/')
  ->checkResponseElement('body', '!/Actions/');
;

