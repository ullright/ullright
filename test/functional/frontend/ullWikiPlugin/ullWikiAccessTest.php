<?php
//php symfony test:all
//php symfony test:functional frontend ullWiki


$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$dgsList = $b->getDgsUllWikiList();

// create additional wiki doc
$wiki = new UllWiki();
$wiki->subject = 'Testdoc readable for logged in users';
$wiki->UllWikiAccessLevel = Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('logged_in_readable');
$wiki->save();

$b
  ->diag('list - not logged in')
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 1) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Testdoc')
;

$b
  ->diag('list - logged in as testuser - testuser member of the wiki-admins, therefore he can read both the public and the private doc')
  ->get('ullAdmin/index')
  ->loginAsTestUser()
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Testdoc')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'Another Testdoc')
  ->checkResponseElement($dgsList->get(3, 'subject'), 'Testdoc readable for logged in users')
;

$b
  ->diag('list - logged in as master admin - should see all')
  ->get('ullUser/logout')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullWiki/list')
  ->isStatusCode(200)
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'list')
  ->checkResponseElement($dgsList->getFullRowSelector(), 3) // number of rows
  ->checkResponseElement($dgsList->get(1, 'subject'), 'Testdoc')
  ->checkResponseElement($dgsList->get(2, 'subject'), 'Another Testdoc')
  ->checkResponseElement($dgsList->get(3, 'subject'), 'Testdoc readable for logged in users')
;

$b
  ->diag('edit - not logged in -> display login')
  ->get('ullUser/logout')
  ->get('ullWiki/edit/docid/1')
  ->isRedirected()
  ->followRedirect()
  ->responseContains('Please login')
;

$b
  ->diag('edit Testdoc - logged in test_user -> read only access')  
  ->get('ullAdmin/index')
  ->loginAsTestUser()
  ->get('ullWiki/edit/docid/1')  
  ->isRedirected()
  ->followRedirect()
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'show')    
  ->isRequestParameter('docid', '1')
  ->isRequestParameter('no_write_access', 'true')
;

$b
  ->diag('edit Another Testdoc - logged in test_user -> normal write access')  
  ->get('ullWiki/edit/docid/2')  
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')    
  ->isRequestParameter('docid', '2')
  ->responseContains('Save and show')
;

$b
  ->diag('edit Testdoc - logged in as Masteradmin -> normal write access')
  ->get('ullUser/logout')
  ->get('ullAdmin/index')
  ->loginAsAdmin()
  ->get('ullWiki/edit/docid/1')  
  ->isRequestParameter('module', 'ullWiki')
  ->isRequestParameter('action', 'edit')    
  ->isRequestParameter('docid', '1')
  ->responseContains('Save and show')
;

//delete testuser's Wiki-Admins membership
Doctrine::getTable('UllEntityGroup')->findOneByUllGroupId(
  Doctrine::getTable('UllGroup')->findOneByDisplayName('WikiAdmins')->id
)->delete();

$b
  ->diag('show: check access to a restricted document')
  ->get('ullUser/logout')
  ->get('ullWiki/show/docid/2')
  ->loginAsTestUser()
  ->isRedirected()
  ->followRedirect()
  ->responseContains('No Access')
;




