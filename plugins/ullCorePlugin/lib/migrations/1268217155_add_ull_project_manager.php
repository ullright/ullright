<?php

class AddUllProjectManager extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ull_project_manager', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'autoincrement' => true,
              'primary' => true,
             ),
             'namespace' => 
             array(
              'type' => 'string',
              'length' => 32,
             ),
             'ull_user_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'ull_project_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'created_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'updated_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'creator_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'updator_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
    }
    
    public function postUp()
    {
      $this->createForeignKey('ull_project_manager', 'ull_project_manager_creator_user_id_ull_entity_id', array(
         'name' => 'ull_project_manager_creator_user_id_ull_entity_id',
         'local' => 'creator_user_id',
         'foreign' => 'id',
         'foreignTable' => 'ull_entity',
         ));
      $this->createForeignKey('ull_project_manager', 'ull_project_manager_updator_user_id_ull_entity_id', array(
         'name' => 'ull_project_manager_updator_user_id_ull_entity_id',
         'local' => 'updator_user_id',
         'foreign' => 'id',
         'foreignTable' => 'ull_entity',
         ));
      $this->createForeignKey('ull_project_manager', 'ull_project_manager_ull_user_id_ull_entity_id', array(
         'name' => 'ull_project_manager_ull_user_id_ull_entity_id',
         'local' => 'ull_user_id',
         'foreign' => 'id',
         'foreignTable' => 'ull_entity',
         ));
      $this->createForeignKey('ull_project_manager', 'ull_project_manager_ull_project_id_ull_project_id', array(
         'name' => 'ull_project_manager_ull_project_id_ull_project_id',
         'local' => 'ull_project_id',
         'foreign' => 'id',
         'foreignTable' => 'ull_project',
         'onUpdate' => NULL,
         'onDelete' => 'CASCADE',
         ));      
    }

    public function down()
    {
      throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
    }
}
