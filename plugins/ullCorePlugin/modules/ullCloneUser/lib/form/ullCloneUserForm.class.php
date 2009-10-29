<?php
class ullCloneUserForm extends ullTableToolForm
{  
  /**
   * Override getting the default values form the object
   *
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    $defaults = $this->getDefaults();
    
//    var_dump($defaults);
    
//    
//    if (!$this->getObject()->exists())
//    {
//      foreach ($defaults as $key => $value)
//      {
//        var_dump($key);
//        var_dump($this->columnsConfig[$key]->getAccess());
//        if ($this->columnsConfig[$key]->getAccess() == 'w')
//        {
//          $defaults[$key] = null;
//        }
//      }
//    }
    
    
    $this->setDefaults($defaults);
  }

}