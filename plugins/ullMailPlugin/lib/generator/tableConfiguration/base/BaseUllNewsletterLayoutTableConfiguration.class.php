<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllNewsletterLayoutTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Newsletter layout', null, 'ullMailMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
    $this->setListColumns(array(
      'name',
      'is_default',
    ));
    
    $this->setPlugin('ullMailPlugin');
    $this->setBreadcrumbClass('ullNewsletterBreadcrumbTree');    
  }  
  
}