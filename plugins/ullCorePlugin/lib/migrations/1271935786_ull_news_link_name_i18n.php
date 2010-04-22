<?php

class UllNewsLinkNameI18n extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_news_translation', 'link_name', 'string', 255);
    $this->removeColumn('ull_news', 'link_name');
  }

  public function down()
  {
    $this->removeColumn('ull_news_translation', 'link_name');
    $this->addColumn('ull_news', 'link_name', 'string', 255);
  }
}
