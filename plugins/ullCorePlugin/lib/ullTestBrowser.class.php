<?php

class ullTestBrowser extends sfDoctrineTestBrowser
{

  public function loginAsAdmin()
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
      ->post('/ullUser/login', array('login' => array('username' => 'admin', 'password' => 'admin')))
      ->isRedirected()
      ->followRedirect()
    ;

    return $this;
  }

  public function loginAsTestUser($password = 'test')
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
      ->post('/ullUser/login', array('login' => array('username' => 'test_user', 'password' => $password)))
      ->isRedirected()
      ->followRedirect()
    ;

    return $this;
  }

}

