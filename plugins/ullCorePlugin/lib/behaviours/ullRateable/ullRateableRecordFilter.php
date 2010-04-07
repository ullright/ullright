<?php
class ullRateableRecordFilter extends Doctrine_Record_Filter
{
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
    throw new Doctrine_Record_UnknownPropertyException(sprintf('blub "%s" on "%s"', $name, get_class($record)));
  }

  public function filterGet(Doctrine_Record $record, $name)
  {
    if ($name == 'average_rating')
    {
      return $record->getAvgRating();
    }
    else
    {
      throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
    }
  }
}
