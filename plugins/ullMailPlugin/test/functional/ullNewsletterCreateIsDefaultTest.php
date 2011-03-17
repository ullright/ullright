<?php

  include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';
  
  $b = new ullTestBrowser(null, null, array('configuration' => $configuration));
  $path = dirname(__FILE__);
  $b->setFixturesPath($path);
  $b->resetDatabase();
  
  $dgsEdit = $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(), array());;
  
  $b
    ->info('go tho Newsletter/create and login')
    ->get('ullNewsletter/create')
    ->loginAsAdmin()
    ->isStatusCode(200)
    ->isRequestParameter('module', 'ullNewsletter')
    ->isRequestParameter('action', 'create')
    
    ->info('check the default layout (there shouldn\'t be one)')
    ->checkResponseElement($dgsEdit->get(4, 2) . ' select option[selected]', '')
  ;
  
  $layout = new UllNewsletterLayout();
  $layout->name = 'testLayout';
  $layout->html_body = '<html><body>test</body></html>';
  $layout->is_default = true;
  $layout->save();
  
  $b
    ->info('create a new layout and make it default')
    ->get('ullNewsletter/create')
    ->checkResponseElement($dgsEdit->get(4, 2) . ' select option[selected]', 'testLayout')
    
    //->dumpDie()
;
