<?php 

class UllVentoryItemSoftwareColumnConfigCollection extends ullColumnConfigCollection
{
  protected
    $itemSoftware
  ;
  
  public function __construct($modelName, $itemSoftware, $defaultAccess = null, $requestAction = null)
  {
    $this->itemSoftware = $itemSoftware;
    parent::__construct($modelName, $defaultAccess, $requestAction);
  }

  public static function build($itemSoftware, $defaultAccess = null, $requestAction = null)
  {
    $c = new self('UllVentoryItemSoftware', $itemSoftware, $defaultAccess, $requestAction);
    $c->buildCollection();
    
    return $c;
  }
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->create('enabled')
      ->setMetaWidgetClassName('ullMetaWidgetCheckbox')
    ;
    
    $this['ull_ventory_software_license_id']
      ->setMetaWidgetClassName('ullMetaWidgetUllVentorySoftwareLicense')
      ->setOption('ull_ventory_software_id', $this->itemSoftware->UllVentorySoftware->id)
      ->setValidatorOption('required', false)
    ;
    
    $this->disable(array('creator_user_id', 'created_at', 'updator_user_id', 'updated_at', 'ull_ventory_item_id'));
    
    $this['id']
      ->setAccess('w')
      ->setWidgetOption('is_hidden', true)
    ;
    
    $this['ull_ventory_software_id']->setWidgetOption('is_hidden', true);
    
    $this['comment']
      ->setMetaWidgetClassName('ullMetaWidgetString')
      ->setWidgetAttribute('size', 24)
    ;

//    var_dump($this->collection);die;
    
  }     
  
  public function getItemSoftware()
  {
    return $this->itemSoftware;
  }  
  
}