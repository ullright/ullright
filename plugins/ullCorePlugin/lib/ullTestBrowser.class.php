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
        'id',
        'first_name',
        'last_name',
        'username',
        'email',
        'location',
        'department'
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
        'due_date',
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
        'due_date',
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
        'priority',
        'due_date',
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
        'project',
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
  
  public function getDgsUllPhoneList()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td', array(),      
      array(
        'name',
        'phone_extension',
        'mobile_number',
        'email_link',
        'location',
        'department',
        'job_title',
       ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }
  
  public function getDgsUllPhoneListLocationHeader()
  {
    $s = new ullDomGridSelector('table > tbody', 'tr', 'td > div > span > b', array(), array('location_name'));
    
    return $s;
  }
  
  public function getDgsUllTimeEditList()
  {
    $s = new ullDomGridSelector('table#ull_time_edit_list > tbody', 'tr', 'td', array(),      
      array(
        'icon',
        'project_name',
        'duration',
        'comment',
       ),
      'table > thead > tr', 'th'
    );
    
    return $s;
  }
  
  public function getDgsUllTimeList()
  {
    $s = new ullDomGridSelector('table#ull_time_list > tbody', 'tr', 'td', array(),      
      array(
        'day',
        'time_reporting',
        'time_total',
        'project_reporting',
        'project_total',
        'delta',
       )
    );
    
    return $s;
  }
  
  public function getDgsUllTimeListTimeForToday()
  {
    $s = new ullDomGridSelector('table#ull_time_list > tbody', 'tr.ull_time_today', 'td', array(),      
      array(
        'day',
        'time_reporting',
        'time_total',
        'project_reporting',
        'project_total',
        'delta',
       )
    );
    
    return $s;
  }
  
  public function getDgsUllTimeListTableSum()
  {
    $s = new ullDomGridSelector('table#ull_time_list > tbody', 'tr.list_table_sum', 'td', array(),      
      array(
        'day',
        'time_reporting',
        'time_total',
        'project_reporting',
        'project_total',
        'delta',
       )
    );
    
    return $s;
  }
  
  public function getDgsUllTimeListReportProject()
  {
    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),      
      array(
        'project',
        'duration',
       )
    );
    
    return $s;
  }
  
  public function getDgsUllTimeReportProjectByUser()
  {
    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),      
      array(
        'user',
        'duration',
       )
    );
    
    return $s;
  }  
  
  public function getDgsUllTimeReportProjectDetails()
  {
    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),      
      array(
        'edit',
        'date',
        'duration',
        'comment',
       )
    );
    
    return $s;
  }   
  
  public function getDgsUllTableToolList()
  {
    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(), array());
    
    return $s;
  }

  public function getDgsUllTableToolUllFlowColumnConfig()
  {
    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'label_translation_en',
        'ull_flow_app_id',
      	'sequence',
        'ull_column_type_id',
        'options',
        'is_enabled',
        'is_mandatory',
        'is_subject',
        'is_priority',
        'is_tagging',
        'is_project',
        'default_value'
      )
    );
    
    return $s;
  }
  
  public function getDgsEditDeleteButton()
  {
    $s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'title_translation_en',
        'parent_ull_cms_item_id',
        'is_active'
      )
    );
    
    return $s;
  }
  
}



