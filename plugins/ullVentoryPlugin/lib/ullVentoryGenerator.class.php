<?php

class ullVentoryGenerator extends ullTableToolGenerator
{
  protected
//    $formClass = 'ullVentoryForm',
    
    $columnsNotShownInList = array(
    )     
  ;
    
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = 'r')
  {
    $this->modelName = 'UllVentoryItem';
    
    parent::__construct($this->modelName, $defaultAccess);
    
  }  
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    parent::buildColumnsConfig();
    
    unset(      
      $this->columnsConfig['creator_user_id'],
      $this->columnsConfig['created_at']      
    );
    
    $this->columnsConfig['updated_at']['metaWidget']  = 'ullMetaWidgetDate';
    
    if ($this->requestAction == 'edit')
    {
      unset(
        $this->columnsConfig['id'],        
        $this->columnsConfig['updator_user_id'],
        $this->columnsConfig['updated_at']
      );
    }

//    //configure subject
//    $this->columnsConfig['subject']['widgetAttributes']['size'] = 50;
    $this->columnsConfig['ull_ventory_item_model_id']['metaWidget']  = 'ullMetaWidgetAjaxSelect';
//    $this->columnsConfig['ull_ventory_item_model_id']['metaWidget']  = 'ullMetaWidgetUllVentoryItemModel';
    
//    
//    //configure body
//    $this->columnsConfig['body']['metaWidget']  = 'ullMetaWidgetFCKEditor';
//    $this->columnsConfig['body']['label']       = 'Text';
//    
//    // configure access level
//    $this->columnsConfig['ull_ventory_access_level_id']['label']       = __('Access level');
//    
//    // configure tags
//    $this->columnsConfig['duplicate_tags_for_search']['label']       = 'Tags';
//    $this->columnsConfig['duplicate_tags_for_search']['metaWidget']  = 'ullMetaWidgetTaggable';
    
//    var_dump($this->columnsConfig);die;
  }
}