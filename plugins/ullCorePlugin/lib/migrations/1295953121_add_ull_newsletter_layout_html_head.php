<?php

class AddUllNewsletterLayoutHtmlHead extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_newsletter_layout', 'html_head', 'string', 3000);
  }

  public function down()
  {
    $this->removeColumn('ull_newsletter_layout', 'html_head');
  }
}
