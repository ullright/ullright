<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllRecord extends BaseUllRecord
{
  /**
   * This record hook is a workaround for the following problem:
   * When only i18n-columns were changed Doctrine sees the record
   * as non-modified (which is correct from its point of view,
   * since only the Translation table experienced changes).
   * However, from the user's pov the record was indeed updated,
   * so we manually force Doctrine to 'update' the record -
   * consequently invoking the Timestampable behavior.
   */
  public function preSave($event)
  {
    //check if this record adopts the i18n behavior
    //and if it does, check if some columns were modified
    $references = $this->getReferences();
    if (isset($references['Translation']) && $references['Translation']->isModified())
    {
      switch($this->state())
      {
        case Doctrine_Record::STATE_CLEAN:
          $this->state(Doctrine_Record::STATE_DIRTY);
          break;
        //ignore STATE_TCLEAN because this workaround is only
        //needed for non-transient records
        
        //do we need to handle additional states
      }
    }
  }
}