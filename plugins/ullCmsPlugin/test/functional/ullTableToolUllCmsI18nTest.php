<?php
include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$page = Doctrine::getTable('UllCmsPage')->findOneBySlug('homepage');
$id = $page['id'];

$b
  ->diag('edit cms page in german')
  ->get('/ullCms/edit/id/' . $id)
  ->loginAsAdmin()
  ->click('Deutsch')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullCms')
    ->isParameter('action', 'edit')
    ->isParameter('id', $id)
  ->end()
  ->setField('fields[title_translation_en]', 'Go Home')
  ->setField('fields[title_translation_de]', 'Geh zaus')
  ->click('Speichern und zurück zur Liste')
;  

$b
  ->diag('check list in german')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullCms')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->matches('/Geh zaus/')
  ->end()
;  

$b
  ->diag('check list in english')
  ->click('English')
  ->isRedirected()
  ->followRedirect()
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'ullCms')
    ->isParameter('action', 'list')
  ->end()
  ->with('response')->begin()
    ->matches('/Go Home/')
  ->end()
;  