<?php

class AddUllFlowDueDate extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_flow_doc', 'due_date', 'datetime');
    $this->addColumn('ull_flow_column_config', 'is_due_date', 'boolean');
  }

  public function down()
  {
    $this->removeColumn('ull_flow_doc', 'due_date');
    $this->removeColumn('ull_flow_column_config', 'is_due_date');
  }
}
