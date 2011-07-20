<?php

/**
 * PluginUllCourseBooking
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginUllCourseBooking extends BaseUllCourseBooking
{
  
  /**
   * pre save hook
   */
  public function preSave($event)
  {
    $this->handleIsPaid();
  }

  /**
   * post save hook
   * 
   * @param unknown_type $event
   */
  public function postSave($event)
  {
    $this->UllCourse->updateProxies();    
  }
  
  /**
   * Automatically set the marked_as_paid fields
   */
  protected function handleIsPaid()
  {
    if ($this['is_paid'])
    {
      $this['marked_as_paid_at'] = date('Y-m-d H:m:s');
      
      $user = UllUserTable::findLoggedInUser();
      
      // fallback to admin for fixture loading
      if (!$user)
      {
        $userId = 1;
      }
      else
      {
        $userId = UllUserTable::findLoggedInUser()->id;  
      }
      
      $this['marked_as_paid_ull_user_id'] = $userId;  
    }
    else
    {
      $this['marked_as_paid_at'] = null;
      $this['marked_as_paid_ull_user_id'] = null;  
    }    
  }

}