<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllNewsletterEditionTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
//    $this->setName(__('News', null, 'ullNewsMessages'));
//    $this->setSearchColumns(array('title'));
//    $this->setOrderBy('activation_date DESC');
    $this->setForeignRelationName(__('Layout', null, 'ullMailMessages'));
  }  
  
}