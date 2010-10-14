<?php

class AddUllFlowDocsMailFlags extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_flow_doc', 'owner_due_reminder_sent', 'boolean', 1, array('default' => 0));
    $this->addColumn('ull_flow_doc', 'owner_due_expiration_sent', 'boolean', 1, array('default' => 0));
    $this->addColumn('ull_flow_doc', 'creator_due_expiration_sent', 'boolean', 1, array('default' => 0));
  }

  public function down()
  {
    $this->removeColumn('ull_flow_doc', 'owner_due_reminder_sent');
    $this->removeColumn('ull_flow_doc', 'owner_due_expiration_sent');
    $this->removeColumn('ull_flow_doc', 'creator_due_expiration_sent');
  }
}
