<?php

class AddUllCmsPageImages extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_cms_item', 'preview_image', 'string', 255);
    $this->addColumn('ull_cms_item', 'image', 'string', 255);
  }

  public function down()
  {
    $this->removeColumn('ull_cms_item', 'preview_image');
    $this->removeColumn('ull_cms_item', 'image');    
  }
}

