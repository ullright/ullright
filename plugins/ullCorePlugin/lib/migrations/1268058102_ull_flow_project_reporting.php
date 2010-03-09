<?php

class UllFlowProjectReporting extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('ull_time_reporting', 'linked_model', 'string', 128);
    $this->addColumn('ull_time_reporting', 'linked_id', 'integer');
    
    $this->addColumn('ull_flow_doc', 'ull_project_id', 'integer');
    $this->addColumn('ull_flow_app', 'enter_effort', 'boolean');
    
    $this->addColumn('ull_project', 'is_routine', 'boolean');
    $this->addColumn('ull_project', 'is_default', 'boolean');
    
  }

  public function postUp()
  {
//    $this->createForeignKey('ull_flow_doc', 'ull_flow_doc_ull_time_reporting_id', array(
//       'name' => 'ull_flow_doc_ull_time_reporting_id',
//       'local' => 'id',
//       'foreign' => 'id',
//       'foreignTable' => 'ull_entity',
//    ));

    $columnType = new UllColumnType();
    $columnType['namespace'] = 'ullCore';
    $columnType['class'] = 'ullMetaWidgetUllProject';
    $columnType['label'] = 'UllProject';
    $columnType->save();    
  }

  public function down()
  {
  }
}
