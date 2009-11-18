<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllCloneUser extends BaseUllCloneUser
{
  
  /**
   * Don't allow to set invalid clone user columns
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#set($fieldName, $value, $load)
   */
  public function set($fieldName, $value, $load = true)
  {
    $validColumns = array_merge(
      sfConfig::get('app_ull_user_clone_user_columns'),
      array(
        'id',
        'parent_ull_user_id',
        'created_at',
        'creator_ull_user_id',
        'updated_at',
        'updator_user_id',
        'version',
      )  
    );
    
    if (in_array($fieldName, $validColumns))
    {
      parent::set($fieldName, $value, $load);
    }  
    else
    {
      throw new InvalidArgumentException('Column is not a valid clone user column: ' . $fieldName);
    }
  }
  
//  protected 
//    $parentCache
//  ;
  
//  protected function getCachedParent()
//  {
//    if (!$this->parentCache)
//    {
//      // why returns $this->parent_ull_user_id an object and not an id ?!?
//     $parent_ull_user_id = $this->parent_ull_user_id->id;
//     
////     var_dump($this->id);
////      
////      if (!$parent_ull_user_id)
////      {
////        $parent_ull_user_id = Doctrine::getTable('UllCloneUser')->findOneById($this->id)->parent_ull_user_id;
////        var_dump('retrieving parent_ull_user_id');
////      }      
////      
////      var_dump($parent_ull_user_id);
//      
//      $this->parentCache = Doctrine::getTable('UllUser')->findOneById($parent_ull_user_id);
//      
//      if (!$this->parentCache)
//      {
//        $this->parentCache = new UllUser;
//      }
//    }
//    
//    return $this->parentCache;
//  }
  
//  public function setUp()
//  {
//    parent::setUp();
//    
//    var_dump($this->toArray());
//    
//  }
  
//  /**
//   * Transparently load the parent's values if none are set natively
//   * 
//   * Overwrites Doctrine_Record::get() method
//   * 
//   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#get($fieldName, $load)
//   */
//  public function get($fieldName, $load = true)
//  {
//    var_dump($fieldName);
//    
//    $value = parent::get($fieldName, $load);
//    
//    var_dump((string) $value);
//    
//////    if ($value === null && $this->getTable()->hasColumn($fieldName))
////    if ($value === null && $this->getTable()->hasColumn($fieldName) && !in_array($fieldName, array('id', 'parent_ull_user_id')))
////    {
////      $value = $this->getCachedParent()->$fieldName;
////      var_dump($fieldName);
////      var_dump($value);
//////      var_dump($this->rawGet($fieldName));
////      var_dump($this->Parent->get($fieldName));
////      var_dump($this->getCachedParent()->get($fieldName));
//////      var_dump($this->toArray());
////      
//////      $value = $this->Parent->$fieldName;
////    }
//
//    return $value;
//  }
  
  
  
//  public function __call($method, $arguments)
//  {
//    die($method);
//  }

}