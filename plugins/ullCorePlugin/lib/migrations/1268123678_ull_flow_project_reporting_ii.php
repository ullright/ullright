<?php

class UllFlowProjectReportingIi extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_flow_column_config', 'is_project', 'boolean');
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
