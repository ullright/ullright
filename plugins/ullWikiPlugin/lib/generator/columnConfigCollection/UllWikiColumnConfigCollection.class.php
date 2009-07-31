<?php

class UllWikiColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('read_counter', 'edit_counter', 'deleted', 'version', 'creator_user_id', 'created_at'));
    
    $this['updated_at']->setMetaWidgetClassName('ullMetaWidgetDate');
    
    //configure subject
    $this['subject']
      ->setLabel('Subject')
      ->setWidgetAttribute('size', 50)
      ->setMetaWidgetClassName('ullMetaWidgetLink');
    
    //configure body
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
      ->setLabel('Text');
    
    // configure access level
    $this['ull_wiki_access_level_id']->setLabel(__('Access level'));
    
    // configure tags
    $this['duplicate_tags_for_search']
      ->setLabel('Tags')
      ->setMetaWidgetClassName('ullMetaWidgetTaggable');
    
    if ($this->isCreateOrEditAction())
    {
      $this->disable(array('id', 'updator_user_id', 'updated_at'));
    }    

    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'subject', 'updator_user_id', 'updated_at'));
    } 
  }
}