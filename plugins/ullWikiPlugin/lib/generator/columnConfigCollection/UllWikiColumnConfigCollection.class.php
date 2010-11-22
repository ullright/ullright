<?php

class UllWikiColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('read_counter', 'edit_counter', 'deleted_at', 'version', 'creator_user_id', 'created_at'));
    
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
    
    $this['is_outdated']->setLabel(__('Is outdated', null, 'ullWikiMessages'));
    
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
      $this->disableAllExcept(array('id', 'subject'));
      $this->enable(array('updator_user_id', 'updated_at'));
    } 
  }
}