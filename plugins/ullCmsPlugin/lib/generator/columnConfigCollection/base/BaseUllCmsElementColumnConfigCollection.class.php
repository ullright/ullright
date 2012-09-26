<?php 

class BaseUllCmsElementColumnConfigCollection extends ullColumnConfigCollection
{
  
  protected 
    $elementType
  ;
  
  /**
   * Load the element type specific column config collection 
   * 
   * Example class name: UllCmsElementPageWithImageColumnConfigCollection
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
    // apps/frontend/lib/generator/columnConfigCollection/UllCmsElementPageWithImageColumnConfigCollection.class.php
    // <?php
    //
    // class UllCmsElementPageWithImageColumnConfigCollection extends UllCmsElementColumnConfigCollection
    // {
    //   protected function applyCustomSettings()
    //   {
    //     parent::applyCustomSettings();
    //    
    //     // add the desired fields here
    //   }  
    // }
    
    $className = 'UllCmsElement' . sfInflector::classify($elementType) . 
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
    
    parent::__construct('UllCmsElement', $defaultAccess, $requestAction);
  }    
  
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    // Disable all, as we only need the artificial columns
    $this->disableAllExcept(array());
  }  

}