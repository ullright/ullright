<?php

/**
 * PluginUllCmsPage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginUllCmsPage extends BaseUllCmsPage
{

  /**
   * preSave hook
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#preSave($event)
   */
  public function preSave($event)
  {
    // Automatically set the name (=navigation item name) to the title of the page if empty
    foreach ($this->Translation as $lang => $translation)
    {
      if (!$this->Translation[$lang]->name)
      {
        $this->Translation[$lang]->name = $this->Translation[$lang]->title;
      }
    }
    
    // Set tags in taggable behaviour
    $this->setTags($this->duplicate_tags_for_search);
        
    parent::preSave($event);
  }
  
  
  /**
   * Helper function to get ullMetaWidgetGallery images as array
   * 
   * TODO: cleanup, refactor
   * 
   * @param integer $column
   * 
   * @return array
   */
  public function getGalleryAsArray($column = 'gallery')
  {
    // ullMetaWidgetGallery can produce empty lines -> cleanup
    $lines = explode("\n", $this->$column);
    $lines = str_replace("\r", '', $lines);
    
    $photos = array();
    foreach ($lines as $line)
    {
      if ($line)
      {
        $photos[] = $line;
      }
    }

    return $photos;
  }
  
}