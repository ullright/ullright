<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllColumnConfigTable extends UllRecordTable
{

  /**
   * returns UllColumnConfig record
   *
   * @param unknown_type $table
   * @param unknown_type $column
   * @return unknown
   */
  public static function findForTableAndColumn($table, $column)
  {
    $q = new Doctrine_Query;
    $q->from('UllColumnConfig cc')
      ->where('cc.db_table_name = ?', $table)
      ->addWhere('cc.db_column_name = ?', $column)
    ;
    return  $q->execute()->getFirst();        
  }
  
  /**
   * adds UllColumnConfig settings to generators columnConfig
   *
   * @param array $columnConfig
   * @param string $table
   * @param string $column
   */
  public static function addColumnConfigArray(ullColumnConfiguration $columnConfig, $table, $column)
  {
    $i18n = false;
    
    if (strstr($column, '_translation_'))
    {
      $i18n = true;
      
      $parts = explode('_', $column);
      $culture = array_pop($parts);
      array_pop($parts);
      $column = implode('_', $parts);      
    }
    
    $dbColumnConfig = self::findForTableAndColumn($table, $column);
    
    if ($dbColumnConfig)
    {
      if ($value = $dbColumnConfig->label)
      {
        $columnConfig->setLabel($value);
      }
      
      if ($value = $dbColumnConfig->options)
      {
        $columnConfig->setWidgetOptions(array_merge($columnConfig->getWidgetOptions(), sfToolkit::stringToArray($value)));
      }
      
      if (!$dbColumnConfig->is_enabled)
      {
        $columnConfig->setAccess(false);
      }
      
      if (!$dbColumnConfig->is_in_list)
      {
        $columnConfig->setIsInList(false);
      }
      
      if ($value = $dbColumnConfig->UllColumnType->class)
      {
        $columnConfig->setMetaWidgetClassName($value);
      }
    }
  }
}