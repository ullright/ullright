<?php

class AddUllCmsPageTagging extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_cms_item', 'duplicate_tags_for_search', 'string', 3000);
  }

  public function down()
  {
    $this->removeColumn('ull_cms_item', 'duplicate_tags_for_search');
  }
}
