<?php

$app = 'myApp';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$my_string_col_selector = 'td + td + td';
$created_at_col_selector = 'td + td + td + td + td + td + td + td';
$updated_at_col_selector = 'td + td + td + td + td + td + td + td + td + td';

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
	->responseContains('list')
	->responseContains('TestTableCaption')
	->responseContains('TestTable for automated testing')
	->checkResponseElement('body', '!/namespace|Namespace/')
	->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar')
	->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
;
	
$b
  ->diag('create')
  ->get('ullTableTool/create/table/TestTable')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('table', 'TestTable')
  ->setField('fields[my_string]', 'Quasimodo')
  ->setField('fields[my_text]', "Hello,\nthis is a new line")
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
  ->setField('fields[my_string]', '')
  ->click('Save')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->checkResponseElement('tr td.form_error', '/Required./', array('position' => 1))
  
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
  ->setField('fields[my_string]', 'Foo Bar edited')  
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
$first_row = $b->getResponseDomCssSelector()->matchAll('tr > td')->getValues();
$b->
  test()->isnt($first_row[7], $first_row[9], 'The edited_at date is different than the created_at date: ' . $first_row[7] . ' vs ' . $first_row[9])
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
  ->checkResponseElement('ul.action_filter input[value="2"]', true)
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
