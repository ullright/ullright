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
    $cc = UllFlowColumnConfigTable::findByAppIdAndSlug($record->ull_flow_app_id, $name);
    
    if ($record->exists())
    {
      $record->setValueByColumn($name, $value);
    }
    else
    {
      // create new UllFlowValue objects
      // we have to do it this way because we want to set 2 attributes: value & ull_flow_column_config_id
      $i = count($record->UllFlowValues);
      $record->UllFlowValues[$i]->value = $value;
      $id = $cc->id;
      $record->UllFlowValues[$i]->ull_flow_column_config_id = $id; 
    }

    // also set the title column of UllFlowDoc
    if ($cc->is_title)
    {
      $record->title = $value;
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