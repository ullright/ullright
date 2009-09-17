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
    $this->setName(__('TestTableLabel', null, 'testMessages'));
    $this->setDescription(__('TestTable for automated testing', null, 'testMessages'));
    $this->setSearchColumns(array('id', 'my_string', 'my_text'));
    $this->setSortColumns('id');
  }
  
}