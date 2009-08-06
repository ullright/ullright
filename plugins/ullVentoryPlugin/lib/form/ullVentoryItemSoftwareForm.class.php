<?php
class ullVentoryItemSoftwareForm extends ullGeneratorForm
{

  /**
   * Load the item-type and item-manufacturer for the given item-model
   * 
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/form/sfFormDoctrine#updateDefaultsFromObject()
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    if ($this->getObject()->exists())
    {
      $this->setDefault('enabled', true);  
    }
  }    
  
}