<?php

/**
 * PluginUllNewsletterLayout
 */
abstract class PluginUllNewsletterLayout extends BaseUllNewsletterLayout
{
  /**
   * This makes the is_default flag unique. So there ist max one entry where the
   * is_default flag is set.
   * 
   * @param unknown_type $event
   */
  public function preSave($event)
  {
    if ($this->is_default)
    {
      $q = new Doctrine_Query;
      $q
        ->update('UllNewsLetterLayout l')
        ->set('l.is_default', '?', false)
        ->where('l.is_default = ?', true)
        ->execute();
      ;
    }
  }

}