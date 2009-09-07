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
      foreach ($record->UllFlowValues as $key => $ullFlowValue)
      {
        if ($ullFlowValue->UllFlowColumnConfig->slug == $name)
        {
          $record->UllFlowValues[$key]->value = $value;
          $cc = $ullFlowValue->UllFlowColumnConfig;
          // also set the subject column of UllFlowDoc
          if ($cc->is_subject)
          {
            $record->subject = $value;
          }          
        }
      }
    }
    else
    {
      $cc = UllFlowColumnConfigTable::findByAppIdAndSlug($record->ull_flow_app_id, $name);
      $ullFlowValue = new UllFlowValue;
      $ullFlowValue->value = $value;
      $ullFlowValue->ull_flow_column_config_id = $cc->id;
      $record->UllFlowValues[] = $ullFlowValue;
      // also set the subject column of UllFlowDoc
      if ($cc->is_subject)
      {
        $record->subject = $value;
      }
    }
    
    return true;
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