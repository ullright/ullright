<?php 

class BaseUllContentElementColumnConfigCollection extends ullColumnConfigCollection
{
  
  protected 
    $elementType
  ;
  
  /**
   * Load the element type specific column config collection 
   * 
   * Example class name: UllContentElementPageWithImageColumnConfigCollection
   * where "PageWithImage" is the camel-cased element type
   * 
   * 
   * @param string $contentType
   * @param string $defaultAccess
   * @param string $requestAction
   */
  public static function build($elementType, $defaultAccess = null, $requestAction = null)
  {
    // Check for element type specific column config collection
    // Example: 
    // apps/frontend/lib/generator/columnConfigCollection/UllContentElementPageWithImageColumnConfigCollection.class.php
    // <?php
    //
    // class UllContentElementPageWithImageColumnConfigCollection extends UllContentElementColumnConfigCollection
    // {
    //   protected function applyCustomSettings()
    //   {
    //     parent::applyCustomSettings();
    //    
    //     // add the desired fields here
    //   }  
    // }
    
    $className = 'UllContentElement' . sfInflector::classify($elementType) . 
      'ColumnConfigCollection'; 
    
    $c = new $className($elementType, $defaultAccess, $requestAction);
    $c->buildCollection();
    
    return $c;
  } 
  

  /**
   * Constructor
   * 
   * @param string $elementType
   * @param unknown_type $defaultAccess
   * @param unknown_type $requestAction
   */
  public function __construct($elementType = null, $defaultAccess = null, $requestAction = null)
  {
    $this->elementType = $elementType;
    
    parent::__construct('UllContentElement', $defaultAccess, $requestAction);
  }    
  
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    // Disable all, as we only need the artificial columns
    $this->disableAllExcept(array());
  }  

}