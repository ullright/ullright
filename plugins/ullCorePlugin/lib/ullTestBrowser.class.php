<?php

class ullTestBrowser extends sfDoctrineTestBrowser
{

  /**
   * Convenience function to login as a given user
   *
   * @param string $user
   * @param string $password
   * @return sfTestBrowser
   */
  public function loginAs($user = 'test_user', $password = 'test')
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
      ->post('/ullUser/login', array('login' => array('username' => $user, 'password' => $password)))
      ->isRedirected()
      ->followRedirect()
    ;

    return $this;
  }  
  
  /**
   * Shortcut to login as admin
   *
   * @return sfTestBrowser
   */
  public function loginAsAdmin()
  {
    $this->loginAs('admin', 'admin');

    return $this;
  }

  /**
   * Shortcut to login as test_user
   *
   * @param string $password
   * @return sfTestBrowser
   */
  public function loginAsTestUser($password = 'test')
  {
    $this->loginAs('test_user', $password);

    return $this;
  }
  
  public function getDgsUllFlowListGeneric()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'actions'     => 1,
        'id'          => 2,
        'app'         => 3,
        'subject'     => 4,
        'priority'    => 5,
        'assigned_to' => 6,
        'status'      => 7,
        'created_by'  => 8,
        'created_at'  => 9,
      ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }
  
  public function getDgsUllFlowListTroubleTicket()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', 
      array(),      
      array(
        'actions'     => 1,
        'id'          => 2,
        'app'         => 3,
        'subject'     => 4,
        'priority'    => 5,
        'assigned_to' => 6,
        'status'      => 7,
        'created_by'  => 8,
        'created_at'  => 9,
      ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }

  public function getDgsUllFlowEditTroubleTicket()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', 
      array(
        'subject'     => 1,
        'information_update' => 2,
        'date'        => 3,
        'email'       => 4,
        'priority'    => 5,
        'attachments' => 6,
        'wiki_links'  => 7,
        'tags'        => 8
      ),      
      array(
        'label'       => 1,
        'value'       => 2,
        'error'       => 3
      )
    );
    
    return $s;
  }   

  public function getDgsUllFlowMemory()
  {
    $s = new ullDomGridSelector('#ull_flow_memories ul > ul', 'li');
    
    return $s;
  }   
  
}

