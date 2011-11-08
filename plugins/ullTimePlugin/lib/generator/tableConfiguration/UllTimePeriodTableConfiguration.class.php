<?php
/**
 * TableConfiguration for UllProject
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllTimePeriodTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Periods', null, 'ullTimeMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('from_date');
    $this
      ->setPlugin('ullTimePlugin')
      ->setBreadcrumbClass('ullTimeBreadcrumbTree')
    ;           
  }
  
}