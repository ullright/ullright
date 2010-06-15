<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddUllFlowIsActive extends Doctrine_Migration_Base
{
    public function up()
    {
      $this->addColumn('ull_flow_app', 'is_active', 'boolean');
    }
    
  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("UPDATE ull_flow_app SET is_active=1");
  }

    public function down()
    {
        $this->removeColumn('ull_flow_app', 'is_active');
    }
}