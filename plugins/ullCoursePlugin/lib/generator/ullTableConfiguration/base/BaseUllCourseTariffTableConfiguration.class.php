<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllCourseTariffTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Course tariffs', null, 'ullCourseMessages'))
      ->setListColumns(array(
          'price',
          'name',
        ))
      ->setToStringColumn('display_name')
      ->setOrderBy('price, name')
    ;
  }
  
}