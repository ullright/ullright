<?php

class UllCmsDefaultPageContentType extends Doctrine_Migration_Base
{
  public function up()
  {
    $defaultId = Doctrine::getTable('UllCmsContentType')->findOneBySlug('default')->id;
    
    $q = new Doctrine_Query;
    $q
      ->update('UllCmsPage p')
      ->set('ull_cms_content_type_id', '?', $defaultId)
      ->where('ull_cms_content_type_id IS NULL')
    ;
    $q->execute(); 
  }

  public function down()
  {
  }
}
