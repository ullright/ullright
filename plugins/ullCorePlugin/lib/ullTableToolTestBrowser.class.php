<?php

/**
 * Test browser for ullTableTool
 * 
 * @author ts
 *
 */

class ullTableToolTestBrowser extends ullTestBrowser
{
  protected $table;
  protected $label;
  protected $link;
  protected $createValues;
  protected $editValues;
  protected $rowCount;
  protected $dgsList;
  
  /**
   * 
   * @param $table table name, e.g. UllLocation
   * @param $label
   * @param $link name of the link on the admin page
   * @param $createValues array of values for the create process
   * @param $editValues array of values for the edit process
   * @param $rowCount count of initial rows
   * @param $dgsList DomGridSelector function name for the list view
   * @param $configuration configuration-param from the bootstrap file
   */
  public function __construct($table, $label, $link, $createValues, $editValues, $rowCount, $dgsList, $configuration)
  {
    $this->table = $table;
    $this->label = $label;
    $this->link = $link;
    $this->createValues = $createValues;
    $this->editValues = $editValues;
    $this->rowCount = $rowCount;
    $this->dgsList = call_user_func(array($this, $dgsList));

    parent::__construct(null, null, array('configuration' => $configuration));
  }
  
  /**
   * Runs a test for ullTableTool with create, edit and delte
   */
  public function executeTest()
  {
    $this
      ->diag('login')
    	->get('ullAdmin/index')
      ->loginAsAdmin()
      ->isStatusCode(200)
      ->with('request')->begin()   
        ->isParameter('module', 'ullAdmin')
        ->isParameter('action', 'index')
      ->end()
    ;
    
    $this
      ->diag('list: '. $this->label)
      ->click($this->link)
      ->isStatusCode(200)   
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'list')
        ->isParameter('table', $this->table)
      ->end() 
      ->with('response')->begin()
        ->contains('<h3>' . $this->label . '</h3>')   
        ->checkElement($this->dgsList->getFullRowSelector(), $this->rowCount)
      ->end()
    ;
    
    $this
      ->diag('create new entry')
      ->click('Create')
      ->isStatusCode(200)   
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'create')
        ->isParameter('table', $this->table)
      ->end()
    ;
    foreach($this->createValues as $name => $value)
    {
      if (is_array($value))
      {
        $this->setField('fields[' . $name . ']', $value[0]);
      }
      else
      {
        $this->setField('fields[' . $name . ']', 'AAAA ' . $value);
      }
    }
    $this 
      ->click('Save and return to list')
      ->isRedirected()
      ->followRedirect()
    ;
    
    $this
      ->diag('list: check new entry')
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'list')
        ->isParameter('table', $this->table)
      ->end()
      ->with('response')->checkElement($this->dgsList->getFullRowSelector(), $this->rowCount + 1)
    ; 
    foreach ($this->createValues as $name => $value)
    {
      if (is_array($value))  //for checkboxes or selects. They have different values for setting a field and check it
      {
        if (strstr($value[1], 'Checkbox_'))   //to check a checkbox
        {
          $this->with('response')->checkElement($this->dgsList->get(1,$name) .' > img[alt="'.$value[1].'"]', true);
        }
        else
        {
          $this->with('response')->checkElement($this->dgsList->get(1,$name), $value[1]);
        }
      }
      else
      {
        $this->with('response')->checkElement($this->dgsList->get(1,$name), 'AAAA ' . $value);
      }
    }
    
    $this
      ->diag('edit entry')
      ->click($this->dgsList->get(1,'edit_delete') . ' > a')
      ->isStatusCode(200)   
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'edit')
        ->isParameter('table', $this->table)
      ->end()
    ;
    foreach($this->editValues as $name => $value)
    {
      if (is_array($value))
      {
        $this->setField('fields[' . $name . ']', $value[0]);
      }
      else
      {
        $this->setField('fields[' . $name . ']', 'AAAA ' . $value);
      }
    }
    $this 
      ->click('Save and return to list')
      ->isRedirected()
      ->followRedirect()
    ;
    
    $this
      ->diag('list: check edited entry')
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'list')
        ->isParameter('table', $this->table)
      ->end()
      ->with('response')->checkElement($this->dgsList->getFullRowSelector(), $this->rowCount + 1)
    ; 
    foreach($this->editValues as $name => $value)
    {
      if (is_array($value))
      {
       if (strstr($value[1], 'Checkbox_'))
        {
          $this->with('response')->checkElement($this->dgsList->get(1,$name) .' > img[alt="'.$value[1].'"]', true);
        }
        else
        {
          $this->with('response')->checkElement($this->dgsList->get(1,$name), $value[1]);
        }
      }
      else
      {
        $this->with('response')->checkElement($this->dgsList->get(1,$name), 'AAAA ' . $value);
      }
    }
    
    $this
      ->diag('list: delete entry')
      ->click($this->dgsList->get(1,'edit_delete') . ' > a + a')
      ->isRedirected()
      ->followRedirect()
      ->with('response')->begin()   
        ->checkElement($this->dgsList->getFullRowSelector(), $this->rowCount)
      ->end()
    ;

    /*
    $b
      ->diag('edit')
      ->click($dgsList->get(1,1) . ' > a')
      ->isStatusCode(200)   
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'edit')
        ->isParameter('table', 'UllLocation')
      ->end() 
      ->setField('fields[name]', 'AAA Zweiter Test')
      ->setField('fields[city]', 'Stadt2')
      ->click('Save and return to list')
      ->isRedirected()
      ->followRedirect()
    ;
    
    $b
      ->diag('list: check edited entry')
      ->with('request')->begin()   
        ->isParameter('module', 'ullTableTool')
        ->isParameter('action', 'list')
        ->isParameter('table', 'UllLocation')
      ->end() 
      ->with('response')->begin()   
        ->checkElement($dgsList->get(1,2), 'AAA Zweiter Test')
        ->checkElement($dgsList->get(1,4), 'Stadt2')
        ->checkElement($dgsList->getFullRowSelector(), 3)
      ->end()
    ;
    
    $b
      ->diag('list: delete entry')
      ->click($dgsList->get(1,1) . ' > a + a')
      ->isRedirected()
      ->followRedirect()
      ->with('response')->begin()   
        ->checkElement($dgsList->get(1,2), 'New York 5th Ave')
        ->checkElement($dgsList->get(2,4), 'Wien')
        ->checkElement($dgsList->getFullRowSelector(), 2)
      ->end()
    ; */
  }
}