<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllFlowDoc extends BaseUllFlowDoc
{

  protected
    $memoryComment = ''
  ;
  
  /**
   * add Doctrine_Filter to handle virtual columns transparently
   *
   */
  public function setUp()
  {
    parent::setUp();
    
    $this->unshiftFilter(new UllFlowDocRecordFilter());
  }

  /**
   * pre save hook
   * 
   * always makes the UllFlowDoc record dirty (=modified)
   * this is necessary, because often only the virtual columns change, but not
   * the own columns change.
   *
   * @param unknown_type $event
   */
  public function preSave($event)
  {
    $this->dirty = $this->dirty == 1 ? 2 : 1;
  }
  
  /**
   * pre insert record hook
   * 
   * set defaults
   *
   * @param unknown_type $event
   */
  public function preInsert($event)
  {
    $this->setDefaults();
    $this->createFirstMemory();
    $this->createMemory();
  }
  
  /**
   * pre update record hook
   *
   * @param unknown_type $event
   */  
  public function preUpdate($event)
  {
    $this->createMemory();   
  }    
  
  
  
  /**
   * transparently set the UllFlowMemory comment
   *
   * @param string $value
   */
  public function setMemoryComment($value)
  {
    $this->memoryComment = $value;
  }
  
  /**
   * transparently get the current UllFlowMemory comment
   *
   * @param string $value
   */
  public function getMemoryComment()
  {
    return $this->memoryComment;
  }  
  
  /**
   * Set the value of a virtual column
   *
   * @param string $ullFlowColumnConfigSlug
   * @param string $value
   * @return mixed
   */
  public function setValueByColumn($ullFlowColumnConfigSlug, $value)
  {
    $ullFlowValue = UllFlowValueTable::findByDocIdAndSlug($this->id, $ullFlowColumnConfigSlug);
    $ullFlowValue->value = $value;
    return $ullFlowValue->save();
  }  
  
  /**
   * Get the value of a virtual column
   *
   * @param string $ullFlowColumnConfigSlug
   * @return mixed
   */
  public function getValueByColumn($ullFlowColumnConfigSlug)
  {
    $ullFlowValue = UllFlowValueTable::findByDocIdAndSlug($this->id, $ullFlowColumnConfigSlug);
    return $ullFlowValue->value;
  }
  
  /**
   * Returns an array with the values of the virtual columns
   * 
   * Example: 
   * array(
   *   'my_subject' => 'This is my subject',
   *   'my_email' => 'darth.vader@ull.at',
   * );
   * 
   * @return array
   */
  public function getVirtualValuesAsArray()
  {
    $values = $this->UllFlowValues;
    
    $return = array();
    
    foreach ($values as $value)
    {
      $return[$value->UllFlowColumnConfig->slug] = $value->value;
    }
    
    return $return;
  }
  
  /**
   * Returns an array containing the virtual columns
   * 
   * Example: 
   * array(
   *   'my_subject',
   *   'my_email', 
   * );
   * 
   * @return array
   */
  public function getVirtualColumnsAsArray()
  {
    $columns = $this->UllFlowApp->UllFlowColumnConfigs;

    $return = array();
    
    foreach ($columns as $column)
    {
      $return[] = $column->slug;
    }
    
    return $return;
  }  
  
  public function getUllFlowMemoriesOrderedByDate()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowMemory x')
      ->addWhere('x.ull_flow_doc_id = ?', $this->id)
      ->orderBy('x.created_at DESC')
    ;      
     
    return $q->execute();
  }
  
  /**
   * get the ull_user_id of the currently logged in user, or '1' for the Master Admin user otherwhise
   *
   * @return int ull_user_id
   */
  protected function getUserId()
  {
    // check for symfony context (none for example for doctrine:data-load)
    if (sfContext::hasInstance())
    {
      return sfContext::getInstance()->getUser()->getAttribute('user_id', 1);
    }
    else
    {
      return 1;
    }
  }
  
  /**
   * Set Defaults
   *
   */
  public function setDefaults()
  {
    if (!$this->ull_flow_action_id)
    {
      $this->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close');
    }
    
    if (!$this->assigned_to_ull_entity_id)
    {
      $this->assigned_to_ull_entity_id = $this->getUserId(); 
    }

    if (!$this->assigned_to_ull_flow_step_id)
    {
      $this->UllFlowStep = $this->UllFlowApp->findStartStep();
    }
  }

  /**
   * Check access for the current UllFlowDoc
   * 
   * returns 'w' for write access, 'r' for read access or false for no access
   *
   * @return mixed 'w', 'r' or false
   */
  public function checkAccess()
  {
    
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      return 'w';
    }
    
    // app-specific global write access
    if (UllUserTable::hasPermission('UllFlow_' . $this->UllFlowApp->slug . '_global_write'))
    {
      return 'w';
    }    
    
    // a user has write access to docs which are assigned to him
    if (UllEntityTable::has($this->UllEntity))
    {
      return 'w';
    }
    
    // read-only access check uses the same query as the list action
    $q = new Doctrine_Query;
    $q->select('x.id');
    $q->from('UllFlowDoc x');
    $q->addWhere('x.id = ?', $this->id);       
    $q = UllFlowDocTable::queryAccess($q, $this->UllFlowApp);
    
    if ($q->count())
    {
      return 'r';
    }
  }  
  
  /**
   * create UllFlowMemory entry
   *
   * @return integer index of new UllFlowMemory
   */
  protected function createMemory()
  {
    $i = count($this->UllFlowMemories);
    $this->UllFlowMemories[$i]->ull_flow_step_id = $this->assigned_to_ull_flow_step_id;
    $this->UllFlowMemories[$i]->ull_flow_action_id = $this->ull_flow_action_id;
    $this->UllFlowMemories[$i]->assigned_to_ull_entity_id = $this->assigned_to_ull_entity_id;
    //TODO: has to be the previous assigned_to_ull_entity_id
    $this->UllFlowMemories[$i]->creator_ull_entity_id = $this->getUserId();
    $this->UllFlowMemories[$i]->comment = $this->memoryComment;        
    
//    sfContext::getInstance()->getLogger()->crit('num of mems: '.count($this->UllFlowMemories));
    
    return $i;
  }

  /**
   * create first UllFlowMemory entry
   * 
   * sets the action to create
   * removes the comment to avoid duplication
   *
   */
  protected function createFirstMemory()
  {
    $i = $this->createMemory();
    $this->UllFlowMemories[$i]->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('create');
    $this->UllFlowMemories[$i]->comment = '';        
  }
}