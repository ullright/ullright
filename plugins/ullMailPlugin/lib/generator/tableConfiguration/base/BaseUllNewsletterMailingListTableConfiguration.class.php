<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllNewsletterMailingListTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Newsletter mailing list', null, 'ullMailMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
    $this->setFilterColumns(array('is_active' => null));
    
    $this->setListColumns(array(
      'name',
      'description',
      'link',
      'is_default',
      'is_subscribed_by_default',
      'is_public',
      'is_active',
    ));
    
    $this->setPlugin('ullMailPlugin');
    $this->setBreadcrumbClass('ullNewsletterBreadcrumbTree');
    
  }
}