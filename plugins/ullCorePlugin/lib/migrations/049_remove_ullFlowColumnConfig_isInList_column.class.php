<?php

class RemoveUllFlowColumnConfigIsInListColumn extends Doctrine_Migration
{
  public function up()
  {
    $this->removeColumn('ull_flow_column_config', 'is_in_list');
  }

  public function down()
  {
    $this->addColumn('ull_flow_column_config', 'is_in_list',
      'boolean', array('type' => 'boolean', 'default' => true));
  }
}