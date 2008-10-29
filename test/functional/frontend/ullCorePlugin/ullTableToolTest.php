<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$my_string_col_selector = 'td + td + td + td + td + td';

$b
  ->diag('login')
	->get('ullAdmin/index')
  ->loginAsAdmin()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullAdmin')
  ->isRequestParameter('action', 'index')
  ->responseContains('ullAdmin')
;

$b
  ->diag('list')
  ->get('ullTableTool/list/table/TestTable')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->checkResponseElement('h3', 'TestTableLabel')
  ->responseContains('TestTable for automated testing')
  ->checkResponseElement('body', '!/namespace|Namespace/')
  ->checkResponseElement('body', '!/useless|Useless/')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar')
  ->checkResponseElement('tr > td + td + td + td > a', 'foobar@example.com')
  ->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
  
;

$b
  ->diag('list - test column headers')
  ->checkResponseElement('tr > th + th + th + th + th + th', 'My custom string label translation en:')
  ->checkResponseElement('table > thead > tr > th', 7) // number of columns
;
  
$b
  ->diag('list - test breadcrumb')  
  ->checkResponseElement('ul#breadcrumbs > li + li + li', 'ullTableTool')
  ->checkResponseElement('ul#breadcrumbs > li + li + li + li', 'Table TestTableLabel')
  ->checkResponseElement('ul#breadcrumbs > li + li + li + li + li', 'List')
;

$b
  ->diag('create')
  ->get('ullTableTool/create/table/TestTable')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('table', 'TestTable')
  ->checkResponseElement('table tr', 10) // number of displayed fields
  ->setField('fields[my_string_translation_en]', 'Quasimodo')
  ->setField('fields[my_text_translation_en]', "Hello,\nthis is a new line")
  ->setField('fields[my_boolean]', 'true')
  ->setField('fields[ull_user_id]', 1)
  ->click('Save')
  ->isRedirected()
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->followRedirect()
;
  
$b
  ->diag('check list for created entry')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->responseContains('list')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar')
  ->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
  ->checkResponseElement('tr + tr + tr > ' . $my_string_col_selector, 'Quasimodo')
;

// force the edited_at date beeing different from the created_at date
usleep(501); 

$b
  ->diag('edit with invalid empty mandatory field')
  ->get('ullTableTool/edit/table/TestTable/id/1')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->responseContains('Foo Bar')
  ->setField('fields[my_string_translation_en]', '')
  ->click('Save')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->checkResponseElement('tr + tr + tr + tr + tr > td + td + td', '/Required./')
;

$b
  ->diag('edit')
  ->get('ullTableTool/edit/table/TestTable/id/1')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->responseContains('Foo Bar')
  ->setField('fields[my_string_translation_en]', 'Foo Bar edited')  
  ->click('Save')
  ->isRedirected()
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->followRedirect()
;

$b
  ->diag('check list for edited entry')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->responseContains('list')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar edited')
  ->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
  ->checkResponseElement('tr + tr + tr > ' . $my_string_col_selector, 'Quasimodo')
;

$b
  ->diag('check if created_at is not equal to updated_at')
  ->get('ullTableTool/edit/table/TestTable/id/1')
;
$created_at = $b->getResponseDomCssSelector()->matchSingle('tr + tr + tr + tr + tr + tr + tr + tr > td + td')->getValue();
$updated_at = $b->getResponseDomCssSelector()->matchSingle('tr + tr + tr + tr + tr + tr + tr + tr + tr + tr> td + td')->getValue();
$b->
  test()->isnt($created_at, $updated_at, 'The edited_at date is different than the created_at date: ' . $created_at . ' vs ' . $updated_at)
;


$b
  ->diag('delete')
  ->get('ullTableTool/delete/table/TestTable/id/3')
  ->isRedirected()
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'delete')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 3)
  ->followRedirect()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->responseContains('list')
  ->checkResponseElement('body', '!/Quasimodo is gone/')
;

$b
  ->diag('filter - search by id')
  ->setField('filter[search]', 2)
  ->click('>')
  ->checkResponseElement('ul.ull_action input[value="2"]', true)
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar More')
;

$b
  ->diag('filter - search for non-existing id')
  ->setField('filter[search]', 666)
  ->click('>')
  ->responseContains('No results found');
;

$b
  ->diag('filter - reset search')
  ->setField('filter[search]', '')
  ->click('>')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar edited')
  ->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
;

$b
  ->diag('filter - search in my_string and my_text column')
  ->setField('filter[search]', 'ore my')
  ->click('>')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar More')  
; 

$b
  ->diag('testing direct link to edit -> save (testing referer handling)')
  ->restart()
  ->get('ullTableTool/edit/table/TestTable/id/1')
  ->loginAsAdmin()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->responseContains('Foo Bar')  
  ->click('Save')
  ->isRedirected()
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->followRedirect()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->responseContains('list')    
;

$b
  ->diag('testing direct link to edit -> cancel (testing referer handling)')
  ->restart()
  ->get('ullTableTool/edit/table/TestTable/id/1')
  ->loginAsAdmin()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->responseContains('Foo Bar')
  ->click('Cancel')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->responseContains('list')    
;

$b
  ->diag('testing direct link to edit -> delete (testing referer handling)')
  ->restart()
  ->get('ullTableTool/edit/table/TestTable/id/1')
  ->loginAsAdmin()
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->responseContains('Foo Bar')
  ->click('Delete')
  ->isRedirected()
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'delete')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->followRedirect()  
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->responseContains('list')
  ->checkResponseElement('body', '!/Foo Bar edited/')
;  

$b
  ->diag('testing list without having a table_config entry')
;
$tableConfig = Doctrine::getTable('UllTableConfig')->findOneByDbTableName('TestTable');
$tableConfig->delete();
$b
  ->get('ullTableTool/list/table/TestTable')
  ->checkResponseElement('h3', 'TestTable')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar More')
;