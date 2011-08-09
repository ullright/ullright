<?php
class BaseUllCourseComponents extends sfComponents
{
  
  /**
   * Edit link component
   */
  public function executeEditLink()
  {
    $this->allow_edit = UllUserTable::hasPermission('ull_course_edit');  
  }
}