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
    // Automatically set the name (=navigation item name) to the title of the page
    foreach($this->Translation as $lang => $translation)
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
  
}