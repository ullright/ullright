<?php

class FixUllNewsletterLayoutHtmlBody extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->changeColumn('ull_newsletter_layout', 'html_body', 'string', 3000);
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
