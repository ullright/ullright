<?php

class AddUllNewsletterMailingListIsPublic extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_newsletter_mailing_list', 'is_public', 'boolean');
  }

  public function down()
  {
  }
}
