<?php 

class BaseUllCmsContentTypeColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['type']
      ->setHelp(__('"page" or "content_block"', null, 'ullCmsMessages'))
    ;
    
    $this->order(array(
      array(
        'name',
        'type',
      ),
      array(
        'slug',
        'id',
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
      ),
    )); 
    
  }
}