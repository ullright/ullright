<?php
/**
 * TableConfiguration for TestTable
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class TestTableTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('TestTableLabel', null, 'testMessages'))
      ->setDescription(__('TestTable for automated testing', null, 'testMessages'))
      ->setCustomRelationName('UllUser->UllLocation', 
        __('Owner', null, 'common') . ' special location')
      ->setSearchColumns(array('id', 'my_email'))
      ->setOrderBy('id')
    ;
  }
  
}