<?php

class AddUllGroupIsActive extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('UllEntity', 'is_active', 'boolean');
  }

  public function down()
  {
    
  }
}
