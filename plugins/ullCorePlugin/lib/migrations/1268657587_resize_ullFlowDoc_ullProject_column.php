<?php

class ResizeUllFlowDocUllProjectColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->changeColumn('ull_flow_doc', 'ull_project_id', 'integer', 8);
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
