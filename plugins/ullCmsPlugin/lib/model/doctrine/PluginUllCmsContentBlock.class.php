<?php

/**
 * PluginUllCmsContentBlock
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginUllCmsContentBlock extends BaseUllCmsContentBlock
{
  
  /**
   * preSave hook
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#preSave($event)
   */
  public function preSave($event)
  {
    // Automatically set the name field if empty
    foreach ($this->Translation as $lang => $translation)
    {
      if (!$this->Translation[$lang]->name)
      {
        $this->Translation[$lang]->name = $this->Translation[$lang]->title;
      }
    }    
    
    parent::preSave($event);
  }  

}