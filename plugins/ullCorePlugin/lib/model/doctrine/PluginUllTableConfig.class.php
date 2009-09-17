<?php

abstract class PluginUllTableConfig extends BaseUllTableConfig
{
  
  /**
   * Return the identifier of the current table 
   *
   * @return mixed
   */
  public function getIdentifier()
  {
    return Doctrine::getTable($this->db_table_name)->getIdentifier();
  }
  
  /**
   * Return the model name as default label
   *
   * @return string
   */
  public function getLabel()
  {
    $label = $this['Translation'][sfDoctrineRecord::getDefaultCulture()]['label'];
//    $this->rawGet('label');
    return ($label) ? $label : $this->db_table_name;
  }
  
  /**
   * Transitional alias for migration to ullTableConfiguration
   * @return unknown_type
   */
  public function getName()
  {
    return $this->getLabel();
  }

  /**
   * Return the identifiers as default search columns
   *
   * @return string
   */
  public function getSearchColumns()
  {
    $search_columns = $this->rawGet('search_columns');
    if (!$search_columns)
    {
      $search_columns = $this->getIdentifier();
      if (is_array($search_columns))
      {
        $search_columns = implode(', ', $search_columns);
      }
    }
    return $search_columns;
  }
  
  /**
   * Return the search columns as an array
   *
   * @return array
   */
  public function getSearchColumnsAsArray()
  {
    $cols = $this->search_columns;
    $cols = explode(',', $cols);
    
    $columns = array();
    foreach($cols as $col)
    {
      $columns[] = trim($col);
    }
    
    return $columns;
  }

}