<?php
/**
 * TableConfiguration for UllStatus
 * 
 * 
 *
 */
class UllUserStatusTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('User status', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
  }
  
}