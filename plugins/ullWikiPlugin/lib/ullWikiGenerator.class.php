<?php

class ullWikiGenerator extends ullTableToolGenerator
{
  protected
    $formClass = 'ullWikiForm',
    
    $columnsNotShownInList = array(
        'body',
        'ull_wiki_access_level_id',
        'duplicate_tags_for_search'
    )     
  ;
    
  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($defaultAccess = 'r')
  {
    $this->modelName = 'UllWiki';
    
    parent::__construct($this->modelName, $defaultAccess);
    
  }  
  
  /**
   * returns the identifier url params
   *
   * @param Doctrine_record $row
   * @return string
   */
  public function getIdentifierUrlParams($row)
  {
    if (!is_integer($row)) 
    {
      throw new UnexpectedArgumentException('$row must be an integer: ' . $row);
    }
    
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }
    
    return 'docid=' . $this->rows[$row]->id;
  }  
  
  /**
   * returns the identifier url params
   *
   * @param Doctrine_record $row
   * @return array
   */
  public function getIdentifierUrlParamsAsArray($row)
  {
    if (!is_integer($row)) 
    {
      throw new UnexpectedArgumentException('$row must be an integer: ' . $row);
    }
    
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }
    
    return array('docid' => $this->rows[$row]->id);
  }    
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {  
    parent::buildColumnsConfig();
    
    unset(      
      $this->columnsConfig['read_counter'],
      $this->columnsConfig['edit_counter'],
      $this->columnsConfig['deleted'],
      $this->columnsConfig['version'],
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

    //configure subject
    $this->columnsConfig['subject']['widgetAttributes']['size'] = 50;
    $this->columnsConfig['subject']['metaWidget']  = 'ullMetaWidgetLink';
    
    //configure body
    $this->columnsConfig['body']['metaWidget']  = 'ullMetaWidgetFCKEditor';
    $this->columnsConfig['body']['label']       = 'Text';
    
    // configure access level
    $this->columnsConfig['ull_wiki_access_level_id']['label']       = __('Access level');
    
    // configure tags
    $this->columnsConfig['duplicate_tags_for_search']['label']       = 'Tags';
    $this->columnsConfig['duplicate_tags_for_search']['metaWidget']  = 'ullMetaWidgetTaggable';
    
  }
}