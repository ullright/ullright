<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddVirtualGroups extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_entity', 'is_virtual_group', 'boolean');
    $this->addColumn('ull_user_version', 'is_virtual_group', 'boolean');
    $this->addColumn('ull_group_version', 'is_virtual_group', 'boolean');
    
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}