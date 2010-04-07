<?php
class ullRateableRecordFilter extends Doctrine_Record_Filter
{
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
    throw new Doctrine_Record_UnknownPropertyException(sprintf('blub "%s" on "%s"', $name, get_class($record)));
  }

  public function filterGet(Doctrine_Record $record, $name)
  {
    if ($name == 'rating_average')
    {
      return $record->getAvgRating();
    }
    if ($name == 'rating_user')
    {
      return $record->getUserRating();
    }
    
    throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
  }
}
