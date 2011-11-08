<?php
/**
 * TableConfiguration for UllProjectManager
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllProjectManagerTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Project Managers', null, 'ullTimeMessages'));
    $this->setListColumns(array('UllProject->name', 'UllUser->display_name', 'UllProject->is_active'));
    $this->setSearchColumns(array('UllProject->name', 'UllUser->display_name'));
    $this->setOrderBy('UllProject->name');
    $this->setFilterColumns(array('UllProject->is_active' => 'checked'));
    $this
      ->setPlugin('ullTimePlugin')
      ->setBreadcrumbClass('ullTimeBreadcrumbTree')
    ;       
    
  }
  
}