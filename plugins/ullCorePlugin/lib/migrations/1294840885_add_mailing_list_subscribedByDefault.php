<?php

class AddMailingListSubscribedByDefault extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_newsletter_mailing_list', 'is_subscribed_by_default', 'boolean', 25, array('default' => 0));
  }

  public function down()
  {
    $this->removeColumn('ull_newsletter_mailing_list', 'is_subscribed_by_default');
  }
}
