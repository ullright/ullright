<?php

class ullFlowTestBrowser extends ullTestBrowser
{

  public function loginAsHelpdeskUser()
  {
    $this
      ->isRedirected()
      ->followRedirect()
      ->isRequestParameter('module', 'ullUser')
      ->isRequestParameter('action', 'noaccess')
      ->isRedirected()
      ->followRedirect()
      ->isStatusCode(200)
      ->isRequestParameter('module', 'ullUser')
      ->isRequestParameter('action', 'login')  
      ->isRequestParameter('option', 'noaccess')
      ->post('/ullUser/login', array('login' => array('username' => 'helpdesk_user', 'password' => 'test')))
      ->isRedirected()
      ->followRedirect()
    ;

    return $this;
  }  
  
}

