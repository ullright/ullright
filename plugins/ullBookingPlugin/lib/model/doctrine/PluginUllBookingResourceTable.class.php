<?php
/**
 */
class PluginUllBookingResourceTable extends UllRecordTable
{
  /**
   * Convenience function to find all booking resources
   * with an enabled 'is_bookable' flag, ordered by the
   * (translated) name column.
   *
   * Can be used as the table method option for sfWidgetFormDoctrineChoice.
   *
   * @return the results of findByIsBookable(true)
   */
  public function findBookableResources()
  {
    $q = new ullQuery('UllBookingResource');
    $q
      ->addWhere('x.is_bookable = ?', true)
      ->addOrderBy('Translation->name')
    ;
    return $q->execute();
  }
}