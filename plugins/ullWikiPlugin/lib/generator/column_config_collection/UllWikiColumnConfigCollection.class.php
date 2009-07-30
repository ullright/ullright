<?php

class UllWikiColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    unset(      
      $this['read_counter'],
      $this['edit_counter'],
      $this['deleted'],
      $this['version'],
      $this['creator_user_id'],
      $this['created_at']      
    );
    
    $this['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');
    
    //configure subject
    $this['subject']->setLabel('Subject');
    $this['subject']->setWidgetAttribute('size', 50);
    $this['subject']->setMetaWidgetClassName('ullMetaWidgetLink');
    
    //configure body
    $this['body']->setMetaWidgetClassName('ullMetaWidgetFCKEditor');
    $this['body']->setLabel('Text');
    
    // configure access level
    $this['ull_wiki_access_level_id']->setLabel(__('Access level'));
    
    // configure tags
    $this['duplicate_tags_for_search']->setLabel('Tags');
    $this['duplicate_tags_for_search']->setMetaWidgetClassName('ullMetaWidgetTaggable');
    
    if ($this->isCreateOrEditAction())
    {
      unset(
        $this['id'],        
        $this['updator_user_id'],
        $this['updated_at']
      );
    }    

    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'subject', 'updator_user_id', 'updated_at'));
    } 
  }
}