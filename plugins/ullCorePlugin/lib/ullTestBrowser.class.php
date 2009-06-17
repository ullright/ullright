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
//      ->isRedirected()
//      ->followRedirect()
      ->isStatusCode(200)
      ->isRequestParameter('module', 'ullUser')
      ->isRequestParameter('action', 'noaccess')
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

  public function getDgsUllUserList()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'actions',
        'id',
        'first_name',
        'last_name',
        'username',
        'email'
       ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }  
  
  public function getDgsUllWikiList()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'actions',
        'id',
        'subject',
        'unknown',
        'updated_by',
        'updated_at'
      ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }  
  
  public function getDgsUllFlowListGeneric()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'actions',
        'app',
        'subject',
        'priority',
        'created_by',
        'created_at'
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
        'actions',
        'id',
        'subject',
        'priority',
        'assigned_to',
        'created_at'
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
        'actions',
        'subject',
        'created_by',
        'created_at'
      ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }

  public function getDgsUllFlowEditTroubleTicket()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', 
      array(
        'subject',
        'information_update',
        'date',
        'email',
        'priority',
        'attachments',
        'wiki_links',
        'tags',
      ),      
      array(
        'label',
        'value',
        'error'
      )
    );
    
    return $s;
  }   

  public function getDgsUllFlowHeader()
  {
    $s = new ullDomGridSelector('ul.ull_flow_edit_header_list', 'li', null, 
      array(
        'created',
        'status',
        'next'
      )    
    );
    
    return $s;
  }

  public function getDgsUllFlowMemory()
  {
    $s = new ullDomGridSelector('#ull_flow_memories ul > li > ul', 'li');
    
    return $s;
  }   
  
  /**
   * ullVentory
   */
  
  public function getDgsUllVentoryList()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'actions',
        'model',
        'inventory_number',
        'serial_number',
        'owner',
        'comment',
        'updated_by',
        'updated_at',
      ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  } 

  public function getDgsUllVentoryEdit()
  {
    $s = new ullDomGridSelector('table#ull_ventory_item > tbody', 'tr', 'td', 
      array(
        'type',
        'manufacturer',
        'model',
        'inventory_number',
        'serial_number',
        'owner',
//        'location',
        'comment',
      ),      
      array(
        'label',
        'value',
        'error',
      )
    );
    
    return $s;
  }   
  
  public function getDgsUllVentoryEditAttributes()
  {
    $s = new ullDomGridSelector('table#ull_ventory_attributes > tbody', 'tr', 'td', array(),      
      array(
        'label',
        'value',
        'comment',
      )
    );
    
    return $s;
  }   
}

