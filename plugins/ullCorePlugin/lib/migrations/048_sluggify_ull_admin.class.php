<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SluggifyUllAdmin extends Doctrine_Migration
{
  public function up()
  {
    $this->changeColumn('ull_employment_type', 'slug', 'string', array('length' => 255));
    $this->changeColumn('ull_user_status', 'slug', 'string', array('length' => 255));
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}