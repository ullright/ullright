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
    $this->setName('My TestTable name');
    $this->setDescription('My TestTable description');
    $this->setSearchColumns('id,my_email');
    $this->setSortColumns('created_at DESC, my_email');
  }
  
}