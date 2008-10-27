<?php

class UllFlowDocRecordFilter extends Doctrine_Record_Filter
{
  public function init()
  {
  }

  public function filterSet(Doctrine_Record $record, $name, $value)
  {
//    var_dump('______' . get_class($record));
//    var_dump($record->toArray());
//    var_dump($name);
//    var_dump($value);
//    var_dump('exists: ' . $record->exists());
//    die;
    if ($record->exists())
    {
      $record->setValueByColumn($name, $value);
    }
    else
    {
      $i = count($record->UllFlowValues);
      $record->UllFlowValues[$i]->value = $value;
      $id = UllFlowColumnConfigTable::findByAppIdAndSlug($record->ull_flow_app_id, $name)->id;
//      var_dump($id);
      
      $record->UllFlowValues[$i]->ull_flow_column_config_id = $id; 
    }
    
    
  }

  public function filterGet(Doctrine_Record $record, $name)
  {
    return $record->getValueByColumn($name);
  }
}