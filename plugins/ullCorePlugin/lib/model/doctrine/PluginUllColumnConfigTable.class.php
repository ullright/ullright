<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllColumnConfigTable extends UllRecordTable
{

  public static function findForTableAndColumn($table, $column)
  {
    $q = new Doctrine_Query;
    $q->from('UllColumnConfig cc')
      ->where('cc.db_table_name = ?', $table)
      ->addWhere('cc.db_column_name = ?', $column)
    ;
    return  $q->execute()->getFirst();        
  }
  
  public static function getColumnConfigArray($table, $column)
  {
    $columnConfig = array();
    
    $dbColumnConfig = self::findForTableAndColumn($table, $column);
    
    if ($dbColumnConfig)
    {
      if ($value = $dbColumnConfig->label)
      {
        $columnConfig['label'] = $value;
      }
      
      if (!$dbColumnConfig->enabled)
      {
        $columnConfig['access'] = null;
      }
    }
    return $columnConfig;
  }
  
}