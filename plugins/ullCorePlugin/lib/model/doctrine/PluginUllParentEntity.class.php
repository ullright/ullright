<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllParentEntity extends BaseUllParentEntity
{

  /**
   * __toString()
   *
   * @return string
   */
  public function __toString()
  {
    
    return $this->display_name;
    
//    if ($this->type == 'group')
//    {
//      $group = sfContext::getInstance()->getI18N()->__('Group', null, 'common');
//      //return $this->display_name . " ($group)";
//      //let's see how this looks...
//      return $this->display_name;
//    }
//    else
//    {
//      return $this->display_name;
//    }
  }
  
}