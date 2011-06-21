<?php

class AddUllWidgetGallery extends Doctrine_Migration_Base
{
  public function up()
  {
  }

  public function postUp()
  {
    $columnType = new UllColumnType();
    $columnType['namespace'] = 'ullCore';
    $columnType['class'] = 'ullMetaWidgetGallery';
    $columnType['label'] = 'Gallery';
    $columnType->save();
  }
  

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
