<?php
/**
 * Doctrine_Record_Filter for UllFormDoc
 * 
 * Allows transparent access to the virtual columns
 * 
 * The virtual columns are defined in UllFlowColumnConfig
 * The values are stored in UllFlowValues
 *
 */
class UllFlowDocRecordFilter extends Doctrine_Record_Filter
{
  public function init()
  {
  }

  /**
   * Logic for setting a virtual ullFlow column
   *
   * @param Doctrine_Record $record
   * @param string $name
   * @param string $value
   */
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
    if ($record->exists())
    {
      $record->setValueByColumn($name, $value);
    }
    else
    {
      $i = count($record->UllFlowValues);
      $record->UllFlowValues[$i]->value = $value;
      $id = UllFlowColumnConfigTable::findByAppIdAndSlug($record->ull_flow_app_id, $name)->id;
      $record->UllFlowValues[$i]->ull_flow_column_config_id = $id; 
    }
  }

  /**
   * Logic for getting a virtual ullFlow column
   *
   * @param Doctrine_Record $record
   * @param string $name
   * @return mixed
   */
  public function filterGet(Doctrine_Record $record, $name)
  {
    return $record->getValueByColumn($name);
  }
  
}