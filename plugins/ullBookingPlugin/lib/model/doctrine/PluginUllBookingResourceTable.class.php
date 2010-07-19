<?php
/**
 */
class PluginUllBookingResourceTable extends UllRecordTable
{
  /**
   * Convenience function to find all booking resources
   * with an enabled 'is_bookable' flag.
   * 
   * Can be used as the table method option for sfWidgetFormDoctrineChoice.
   *  
   * @return the results of findByIsBookable(true)
   */
  public function findBookableResources()
  {
    return $this->findByIsBookable(true);
  }
}