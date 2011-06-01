<?php

class FixUllFlowColumnSlugs extends Doctrine_Migration_Base
{
  public function up()
  {
    $columns = Doctrine::getTable('UllFlowColumnConfig')->findAll();
    
    foreach($columns as $column)
    {
      $column->save();
    }
  }

  public function down()
  {
  }
}
