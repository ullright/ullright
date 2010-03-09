<?php

class UllFlowProjectReportingIii extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->removeColumn('ull_time_reporting');
    $this->removeColumn('ull_time_reporting');
    
    $this->addColumn('ull_project_reporting', 'linked_model', 'string', 128);
    $this->addColumn('ull_project_reporting', 'linked_id', 'integer');    
  }

  public function down()
  {
  }
}
