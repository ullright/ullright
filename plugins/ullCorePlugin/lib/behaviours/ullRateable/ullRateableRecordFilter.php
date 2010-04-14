<?php

/**
 * This record filter adds two properties to a model:
 * 
 * rating_average
 *   the average rating, null if none are set
 * 
 * rating_user
 *   the rating of the currently logged in user, null
 *   if none is set. Throws an exception if there isn't
 *   a logged in user.
 *
 * Obviously, this record filter must not be added to a
 * model not adopting the ullRateable behavior.
 */
class ullRateableRecordFilter extends Doctrine_Record_Filter
{
  /**
   * This record filter does not enable the setting of
   * additional properties
   */
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
    throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
  }

  /**
   * If the requested property is 'rating_average' or 'rating_user'
   * this method retrieves and returns the respective values.
   */
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
