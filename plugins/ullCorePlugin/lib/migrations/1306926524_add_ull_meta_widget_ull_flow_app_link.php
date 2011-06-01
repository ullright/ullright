<?php

class AddUllMetaWidgetUllFlowAppLink extends Doctrine_Migration_Base
{
  public function up()
  {
  }
  
  public function postUp()
  {
    $columnType = new UllColumnType();
    $columnType['namespace'] = 'ullFlow';
    $columnType['class'] = 'ullMetaWidgetUllFlowAppLink';
    $columnType['label'] = 'ullFlow app link';
    $columnType->save();
  }
  

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
