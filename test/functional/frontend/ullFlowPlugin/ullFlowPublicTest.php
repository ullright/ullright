<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsEditTT = $b->getDgsUllFlowEditTroubleTicket();
$dgsEditMem = $b->getDgsUllFlowMemory();
$dgsList = $b->getDgsUllFlowListGeneric();
$dgsListTT = $b->getDgsUllFlowListTroubleTicket();

$troubleTicket = Doctrine::getTable('UllFlowApp')->findOneBySlug('trouble_ticket');
$troubleTicket->is_public = true;
$troubleTicket->save();


$b
  ->diag('ullFlow Home')
  ->get('ullFlow/index')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'index')
  ->click('Trouble ticket tool')
  ->click('All entries')
;  

$b
  ->diag('Trouble ticket list')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 2) // number of rows
;

$b
  ->click('My first trouble ticket')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('div.edit_container input', false)
;  

$b
  ->diag('Try to create (not allowed)')
  ->get('ullFlow/create/app/trouble_ticket')
  ->isRedirected()
  ->followRedirect()
  ->responseContains('Log in')
;

$b
  ->diag('Try to delete (not allowed)')
  ->get('ullFlow/delete/doc/1')
  ->isRedirected()
  ->followRedirect()
  ->responseContains('Log in')
;

$b
  ->diag('Todo list (no docs accessible)')
  ->get('ullFlow/list/app/todo')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), false) // number of rows
;

$b
  ->diag('login as test user: he should still see all tickets')
  ->get('ullAdmin/index')
  ->loginAsTestuser()
  ->get('ullFlow/list/app/trouble_ticket')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullFlow')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsListTT->getFullRowSelector(), 2) // number of rows
;  


