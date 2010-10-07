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

  /**
   * Logic for setting a virtual ullFlow column
   *
   * @param Doctrine_Record $record
   * @param string $name
   * @param string $value
   */
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
    $cc = $this->validateAndGetColumnConfig($record, $name);
    
    $ullFlowValue = null;
    
    // try to get the ullFlowValue from the ullFlowDoc object graph
    foreach ($record->UllFlowValues as $currentValue)
    {
      if ($currentValue->UllFlowColumnConfig->slug == $name)
      {
        $ullFlowValue = $currentValue;
      }
    }
    
    // otherwise create a new ullFlowValue object
    if ($ullFlowValue === null)
    {
      $ullFlowValue = new ullFlowValue();
      $ullFlowValue->UllFlowColumnConfig = $cc;
    }

    $ullFlowValue->value = $value;
    
    // in case of a new ullFlow object add it to the ullFlowDoc object graph
    if (!$ullFlowValue->exists())
    {
      $record->UllFlowValues[] = $ullFlowValue;
    }
    
    
    // also set the native "duplicate" columns of UllFlowDoc
    if ($cc->is_subject)
    {
      $record->subject = $value;
    }
    if ($cc->is_priority)
    {
      $record->priority = $value;
    }
    if ($cc->is_project)
    {
      $record->ull_project_id = $value;
    }
    if ($cc->is_due_date)
    {
      $record->due_date = $value;
    }    
    if ($cc->is_tagging)
    {
      $record->duplicate_tags_for_search = $value;
      // Set tags in taggable behaviour
      $record->setTags($value);
    }        

    return $record;
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
    $cc = $this->validateAndGetColumnConfig($record, $name);
    
    // try to get the ullFlowValue from the ullFlowDoc object graph
    foreach ($record->UllFlowValues as $currentValue)
    {
      if ($currentValue->UllFlowColumnConfig->slug == $name)
      {
        return $currentValue->value;
      }
    }
  }
  
  
  /**
   * Check if the given virtual column exists and return the columnConfig
   * 
   * @param Doctrine_Record $record
   * @param string $name
   * @return ullColumnConfiguration
   * @throws Doctrine_Record_UnknownPropertyException
   */
  protected function validateAndGetColumnConfig(Doctrine_Record $record, $name)
  {
    try
    {
      $cc = UllFlowColumnConfigTable::findByAppIdAndSlug($record->ull_flow_app_id, $name);
    }
    catch (InvalidArgumentException $e)
    {
      throw new Doctrine_Record_UnknownPropertyException('Invalid virtual ullFlow column: ' . $name);
      
      return null;
    }
    
    return $cc;
  }
  
}