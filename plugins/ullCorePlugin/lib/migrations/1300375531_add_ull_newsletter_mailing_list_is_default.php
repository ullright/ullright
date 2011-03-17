<?php

class AddUllNewsletterMailingListIsDefault extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_newsletter_mailing_list', 'is_default', 'boolean');
  }

  public function down()
  {
    $this->removeColumn('ull_newsletter_mailing_list', 'is_default');
  }
}