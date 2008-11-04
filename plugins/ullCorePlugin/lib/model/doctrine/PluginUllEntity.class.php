<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllEntity extends BaseUllEntity
{

  /**
   * __toString()
   *
   * @return string
   */
  public function __toString()
  {
    if ($this->type == 'group')
    {
      $group = sfContext::getInstance()->getI18N()->__('Group', null, 'common');
      return $this->display_name . " ($group)";
    }
    else
    {
      return $this->display_name;
    }
  }

}