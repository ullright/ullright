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
class UllCloneUserRecordFilter extends Doctrine_Record_Filter
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
//    var_dump($record->toArray());
//    var_dump($name);
//    var_dump($value);
    
    $record->$name = $value;

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
    var_dump($name);
    
    return $record->$name;
//    return $record->getValueByColumn($name);
  }
  
}