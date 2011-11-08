<?php
/**
 * TableConfiguration for UllWikiAccessLevel
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllWikiAccessLevelTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Access levels', null, 'ullWikiMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('slug');
    $this
      ->setPlugin('ullWikiPlugin')
      ->setBreadcrumbClass('ullWikiBreadcrumbTree')
    ;        
  }
  
}