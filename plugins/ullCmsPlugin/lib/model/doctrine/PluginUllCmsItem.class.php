<?php

/**
 * PluginUllCmsItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginUllCmsItem extends BaseUllCmsItem
{

  /**
   * String representation
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord#__toString()
   */
  public function __toString()
  {
    return (string) $this->full_path;
  }
  
  
  /**
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#preSave($event)
   */
  public function preSave($event)
  {
    // Create menu name if none given
    // This applies only to UllCmsPage children
//    foreach($this->Translation as $lang => $translation)
//    {
//      if (!$this->Translation[$lang]->name)
//      {
//        $this->Translation[$lang]->name = $this->Translation[$lang]->title;
//      }
//    }
    
    
    // Create the full path cache
    // e.g. "Main navigation - About us - Team"
    // Improves performance and allows proper ordering in list views
    foreach ($this->Translation as $lang => $translation)
    {
      $this->Translation[$lang]->full_path = $this->getFullPathName($this, $lang);
    }

    parent::preSave($event);
  }

  
  /**
   * Get the full path with parent names
   * 
   * @param $parent
   * @return string
   */
  public function getFullPathName($parent = null, $lang = null)
  {
    return implode(' - ', $this->buildFullPathName($parent, $lang));
  }    
  
  
  /**
   * Build the full path name
   * 
   * @param unknown_type $object
   * @return array
   */
  public function buildFullPathName($object = null, $lang = null)
  {
    if ($object === null)
    {
      $object = $this;
    }
    
    $return = array();
    
    if ($object->hasReference('Parent'))
    {
      $return = array_merge($this->buildFullPathName($object->Parent, $lang), $return);
    }

    // We have to refetch the Translations for pages. Why?
    if (!$object->name)
    {
      // Necessary for fixture loading
      if (!$object->exists())
      {
        $object->save();
      }
      $object->refreshRelated('Translation');
    }

    if ($lang)
    {
      $return[] = $object->Translation[$lang]->name;
    }
    else
    {
      $return[] = $object->name;
    }
      
    
    return $return;
  }
  
  
  /**
   * Get the sub items for current item
   *
   * @param boolean $hydration
   * @return mixed
   */
  public function getSubs($hydrationMode = null)
  {
    $q = new ullQuery('UllCmsItem');
    $q
      ->addSelect(array('slug', 'name'))
      ->addWhere('parent_ull_cms_item_id = ?', $this->id)
      ->addWhere('is_active = ?', true)
      ->addOrderby('sequence, name')
    ;
  
    $result = $q->execute(null, $hydrationMode);
  
    return $result;
  }    
  
  
}