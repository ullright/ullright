<?php

class AddUllNewsletterLayoutIsDefault extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_newsletter_layout', 'is_default', 'boolean');
  }

  public function down()
  {
    $this->removeColumn('ull_newsletter_layout', 'is_default');
  }
}
