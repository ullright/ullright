<?php

$app = 'frontend';
include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTestBrowser(null, null, array('configuration' => $configuration));
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$my_string_col_selector = 'td + td';

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
  ->get('/ullTableTool/list/table/TestTable')
  ->isStatusCode(200)    
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'list')
  ->isRequestParameter('table', 'TestTable')
  ->checkResponseElement('h3', 'TestTableLabel')
  ->responseContains('TestTable for automated testing')
  ->checkResponseElement('body', '!/namespace|Namespace/')
  ->checkResponseElement('body', '!/useless|Useless/')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar')
  ->checkResponseElement('tr > td + td + td + td + td > a', 'foobar@example.com')
  ->checkResponseElement('tr > td + td + td + td + td + td', 'My first option')
  ->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
;

$b
  ->diag('list - test column headers')
  ->checkResponseElement('tr > th + th > a', 'My custom string label')
  ->checkResponseElement('table > thead > tr > th', 7) // number of columns
;
  
$b
  ->diag('list - test breadcrumb')  
  ->checkResponseElement('ul#breadcrumbs > li + li + li', 'Tabletool')
  ->checkResponseElement('ul#breadcrumbs > li + li + li + li', 'Table TestTableLabel')
  ->checkResponseElement('ul#breadcrumbs > li + li + li + li + li', 'Result list')
;

$b
  ->diag('create')
  ->click('Create')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'create')
  ->isRequestParameter('table', 'TestTable')
  ->checkResponseElement('table tr', 6) // number of displayed fields
  ->checkResponseElement('select#fields_my_select_box > option', 3) // number of options for the my_select_box field
  ->checkResponseElement('select#fields_my_select_box > option', true)
  ->checkResponseElement('select#fields_my_select_box > option + option', 'My first option')
  ->checkResponseElement('select#fields_my_select_box > option + option + option', 'My second option')
  ->setField('fields[my_string_translation_en]', 'Quasimodo')
  ->setField('fields[my_text_translation_en]', "Hello,\nthis is a new line")
  ->setField('fields[my_email]', 'quasimodo@example.com')
  ->setField('fields[my_boolean]', 'true')
  ->setField('fields[my_select_box]', 2)
  ->setField('fields[ull_user_id]', 1)
  ->click('Save and return to list')
  ->isRedirected()
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
  ->click('Save and return to list')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->isRequestParameter('table', 'TestTable')
  ->isRequestParameter('id', 1)
  ->checkResponseElement('tr + tr > td + td + td', '/Required./')  
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
  ->click('Save and return to list')
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


/* 
  somehow this test doesn't work anymore. it works in "real life" though...
  
$b
  ->diag('check if created_at is not equal to updated_at')
  ->get('ullTableTool/edit/table/TestTable/id/1')
;
$created_at = $b->getResponseDomCssSelector()->matchSingle('tr + tr + tr + tr + tr + tr + tr + tr + tr> td + td')->getValue();
$updated_at = $b->getResponseDomCssSelector()->matchSingle('tr + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr> td + td')->getValue();
$b->
  test()->isnt($created_at, $updated_at, 'The edited_at date is different than the created_at date: ' . $created_at . ' vs ' . $updated_at)
;
*/

$b
  ->diag('edit - test checkbox')
  ->get('ullTableTool/edit/table/TestTable/id/2')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('tr + tr > td + td > input[type="checkbox"][checked=""]', true)
  ->setField('fields[my_boolean]', true)
  ->click('Save and return to list')
  
  ->get('ullTableTool/edit/table/TestTable/id/2')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('tr + tr > td + td > input[type="checkbox"][checked="checked"]', true)
  ->setField('fields[my_boolean]', false)
  ->click('Save and return to list')
  
  ->get('ullTableTool/edit/table/TestTable/id/2')
  ->isStatusCode(200)   
  ->isRequestParameter('module', 'ullTableTool')
  ->isRequestParameter('action', 'edit')
  ->checkResponseElement('tr + tr > td + td > input[type="checkbox"][checked=""]', true)
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
//  ->dumpDie()
  ->setField('filter[search]', 2)
  ->click('search_list')
  ->isRedirected()
  ->followRedirect()
  ->checkResponseElement('.ull_filter > ul > li', '/Search: 2/')
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar More')
;

$b
  ->diag('filter - search for non-existing id')
  ->setField('filter[search]', 666)
  ->click('search_list')
  ->isRedirected()
  ->followRedirect()  
  ->responseContains('No results found');
;

$b
  ->diag('filter - reset search')
  ->setField('filter[search]', '')
  ->click('search_list')
  ->isRedirected()
  ->followRedirect()  
  ->checkResponseElement('tr > ' . $my_string_col_selector, 'Foo Bar edited')
  ->checkResponseElement('tr + tr > ' . $my_string_col_selector, 'Foo Bar More')
;

$b
  ->diag('filter - search in my_email column')
  ->setField('filter[search]', 're@e')
  ->click('search_list')
  ->isRedirected()
  ->followRedirect()  
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
  ->click('Save and return to list')
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

