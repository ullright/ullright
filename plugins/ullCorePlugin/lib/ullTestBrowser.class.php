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
      ->responseContains('Log in')
      ->post('/ullUser/login', array('login' => array('username' => $user, 'password' => $password, 'login_request' => true)))
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

  public function navigateToSearch($withLogin = false, $module = 'ullAdmin', $model = 'ullUser')
  {
    $this
	    ->get($module . '/index');
	    if ($withLogin)
	    {
	     $this->loginAsAdmin();
	    } 
	 $this
	    ->isStatusCode(200)
	    ->isRequestParameter('module', $module)
	    ->isRequestParameter('action', 'index')
	    ->responseContains($module)
	  ;

    $this
	    ->with('response')->begin()
		    ->contains('Advanced search')
		    ->click('Advanced search', array())
	    ->end()
    ;

    $this
	    ->with('request')->begin()
		    ->isParameter('module', $model)
		    ->isParameter('action', 'search')
	    ->end()
	    ->with('response')->begin()
	       ->isStatusCode(200)
	    ->end()
    ;
  }
  
  public function resetSearch($module = 'ullUser')
  {
    $this
      ->diag('Reset search');
    
    $this
      ->with('response')->begin()
	       ->contains('Reset search')
	       ->click('Reset search', array())
      ->end()
    ;

		$this
		  ->with('response')->begin()
		    ->isRedirected(1)
		    ->isStatusCode(302)
		  ->end()
		  ->followRedirect()
		;
		
		$this
		  ->with('request')->begin()
		    ->isParameter('module', $module)
		    ->isParameter('action', 'search')
		  ->end()
		  ->with('response')->begin()
		    ->isStatusCode(200)
		  ->end()
		;
  }
  
  public function getDgsUllUserList()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'actions',
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
    $s = new ullDomGridSelector('#ull_memory ul > li > ul', 'li');
    
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
        'inventory_number',
        'toggle_inventory_taking',
        'type',
        'manufacturer',
        'model',
//        'serial_number',
        'owner',
        'location',
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
        'comment',
//        'location',
        
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
  
  public function getDgsUllVentoryEditSoftware()
  {
    $s = new ullDomGridSelector('table#ull_ventory_software > tbody', 'tr', 'td', array(),      
      array(
        'label',
        'enabled',
        'license',
        'comment',
      )
    );
    
    return $s;
  }  

  public function getDgsUllVentoryOrigin()
  {
    $s = new ullDomGridSelector('div#ull_ventory_memory > table > tbody', 'tr', 'td', 
      array(
        'origin',
        'date',
        'comment'
      ),      
      array(
        'label',
        'value',
        'error',
      )
    );
    
    return $s;
  }

  public function getDgsUllVentoryOwner()
  {
    $s = new ullDomGridSelector('div#ull_ventory_memory > table > tbody', 'tr', 'td', 
      array(
        'owner',
        'comment'
      ),      
      array(
        'label',
        'value',
        'error',
      )
    );
    
    return $s;
  }   

  public function getDgsUllVentoryMemory()
  {
    $s = new ullDomGridSelector('#ull_memory > ul > li > ul', 'li');
    
    return $s;
  }    
}

