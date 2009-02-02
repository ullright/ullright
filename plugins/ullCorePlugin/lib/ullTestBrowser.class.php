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
        'app'         => 2,
        'subject'     => 3,
        'created_by'  => 4,
        'created_at'  => 5,
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
        'subject'     => 3,
        'priority'    => 4,
        'assigned_to' => 5,
        'created_at'  => 6,
      ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }
  
  public function getDgsUllFlowListTodo()
  {
  	$s = new ullDomGridSelector('table > tbody', 'tr', 'td', 
      array(),      
      array(
        'actions'     => 1,
        'subject'     => 2,
        'created_by'  => 3,
        'created_at'  => 4,
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

  public function getDgsUllFlowHeader()
  {
    $s = new ullDomGridSelector('ul.ull_flow_edit_header_list', 'li', null, 
      array(
        'created'     => 1,
        'status'      => 2,
        'next'        => 3
      )    
    );
    
    return $s;
  }

  public function getDgsUllFlowMemory()
  {
    $s = new ullDomGridSelector('#ull_flow_memories ul > li > ul', 'li');
    
    return $s;
  }   
  
}

