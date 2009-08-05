<?php

abstract class UllVentoryDummyUserColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disableAllExcept(array('id', 'display_name',
      'creator_user_id', 'created_at', 'updator_user_id', 'updated_at'));

    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'display_name'));
    } 
  }
}

class UllVentoryOriginDummyUserColumnConfigCollection extends UllVentoryDummyUserColumnConfigCollection
{

}


class UllVentoryStatusDummyUserColumnConfigCollection extends UllVentoryDummyUserColumnConfigCollection
{

}
