<?php

class RenameUllNewsletterLayoutColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->renameColumn('ull_newsletter_layout', 'html_layout', 'html_body');
  }

  public function down()
  {
    $this->renameColumn('ull_newsletter_layout', 'html_body', 'html_layout');
  }
}
