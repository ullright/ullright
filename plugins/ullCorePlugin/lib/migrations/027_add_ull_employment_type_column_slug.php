<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AddUllEmploymentTypeColumnSlug extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_employment_type', 'slug', 'string', array('length' => 64));
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}