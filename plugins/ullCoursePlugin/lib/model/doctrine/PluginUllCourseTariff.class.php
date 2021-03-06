<?php

/**
 * PluginUllCourseTariff
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginUllCourseTariff extends BaseUllCourseTariff
{
  
  /**
   * String representation
   * 
   * @see lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord#__toString()
   */
  public function __toString()
  {
    return (string) $this['display_name'];
  }

  
  /** 
   * Create a readable display column
   * 
   * @param $event
   */
  public function preSave($event)
  {
    /**
     * Create context for fixture loading
     */
    if (sfContext::hasInstance())
    {
      $context = sfContext::getInstance();
    }
    else
    {
      $context = sfContext::createInstance(new frontendConfiguration('prod', false));
    }
    $context->getConfiguration()->loadHelpers(array('Number'));
    
    foreach ($this->Translation as $lang => $translation)
    {
      $this->Translation[$lang]->display_name =
        format_currency($this->price, 'EUR', $lang) .  
        ' ('. 
        $this->Translation[$lang]->name .
        ')'
      ;
    }  
  }

}