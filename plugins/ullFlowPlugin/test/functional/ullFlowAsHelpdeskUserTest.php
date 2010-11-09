<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullFlowTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllFlowListGeneric();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();

$b
  ->diag('ullFlow Home')
  ->get('ullAdmin/index')
  ->loginAsHelpdeskUser()
  ->get('ullFlow/index')
;

$b
  ->diag('combinend list access rights as helpdesk user')
  ->click('All entries')
  ->diag('list - content')
  ->checkResponseElement($dgsList->getFullRowSelector(), 2) //number of rows
  // read access because user is member of "Trouble ticket tool - global read access" group 
  ->checkResponseElement($dgsList->get(1, 'subject'), 'AAA My second trouble ticket')
  // read access because user is member of HelpdeskGroup to which the doc is assigned
  ->checkResponseElement($dgsList->get(2, 'subject'), 'My first trouble ticket')
;

$b
  ->diag('app specific list access rights as helpdesk user')
  ->click('Workflows')
  ->click('Trouble ticket tool')
  ->click('All entries')
  ->diag('list - content')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 2) //number of rows
  // read access because user is member of "Trouble ticket tool - global read access" group 
  ->checkResponseElement($dgsListTT->get(1, 'subject'), 'AAA My second trouble ticket')
  // read access because user is member of HelpdeskGroup to which the doc is assigned
  ->checkResponseElement($dgsListTT->get(2, 'subject'), 'My first trouble ticket')
;