<?php
/**
 * TableConfiguration for UllWiki
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllWikiTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setOrderBy('updated_at desc')
      ->setSearchColumns(array(
        'id',
        'subject',
        'duplicate_tags_for_search',
      ))
    ;
  }
  
}