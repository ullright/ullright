<?php

class AddUllUserParentId extends Doctrine_Migration
{
  public function up()
  {
    $this->addColumn('ull_entity', 'parent_ull_user_id', 'integer', array('length' => 2147483647));
    $this->addColumn('ull_entity_version', 'parent_ull_user_id', 'integer', array('length' => 2147483647));
    $this->addColumn('ull_user_version', 'parent_ull_user_id', 'integer', array('length' => 2147483647));
    $this->addColumn('ull_group_version', 'parent_ull_user_id', 'integer', array('length' => 2147483647));
    $this->addColumn('ull_ventory_origin_dummy_user_version', 'parent_ull_user_id', 'integer', array('length' => 2147483647));
    $this->addColumn('ull_ventory_status_dummy_user_version', 'parent_ull_user_id', 'integer', array('length' => 2147483647));  

    $this->createTable('ull_clone_user_version', array('id' => array('type' => 'integer', 'length' => 8, 'primary' => true), 'namespace' => array('type' => 'string', 'length' => 32), 'first_name' => array('type' => 'string', 'length' => 64), 'last_name' => array('type' => 'string', 'length' => 64), 'display_name' => array('type' => 'string', 'length' => 64), 'username' => array('type' => 'string', 'length' => 64), 'email' => array('type' => 'string', 'length' => 64), 'password' => array('type' => 'string', 'length' => 40), 'sex' => array('type' => 'string', 'length' => 255), 'entry_date' => array('type' => 'date', 'length' => 25), 'deactivation_date' => array('type' => 'date', 'length' => 25), 'separation_date' => array('type' => 'date', 'length' => 25), 'ull_employment_type_id' => array('type' => 'integer', 'length' => 8), 'ull_job_title_id' => array('type' => 'integer', 'length' => 8), 'ull_company_id' => array('type' => 'integer', 'length' => 8), 'ull_department_id' => array('type' => 'integer', 'length' => 8), 'ull_location_id' => array('type' => 'integer', 'length' => 8), 'superior_ull_user_id' => array('type' => 'integer', 'length' => 8), 'phone_extension' => array('type' => 'integer', 'length' => 8), 'is_show_extension_in_phonebook' => array('type' => 'integer', 'length' => 1), 'fax_extension' => array('type' => 'integer', 'length' => 8), 'is_show_fax_extension_in_phonebook' => array('type' => 'integer', 'length' => 1), 'comment' => array('type' => 'string', 'length' => 2147483647), 'ull_user_status_id' => array('type' => 'integer', 'length' => 8, 'default' => '1', 'notnull' => true), 'is_virtual_group' => array('type' => 'integer', 'length' => 1, 'default' => '0'), 'photo' => array('type' => 'string', 'length' => 255), 'parent_ull_user_id' => array('type' => 'integer', 'length' => 8), 'type' => array('type' => 'string', 'length' => 255), 'created_at' => array('type' => 'timestamp', 'length' => 25), 'updated_at' => array('type' => 'timestamp', 'length' => 25), 'creator_user_id' => array('type' => 'integer', 'length' => 8), 'updator_user_id' => array('type' => 'integer', 'length' => 8), 'version' => array('type' => 'integer', 'length' => 8, 'primary' => true), 'reference_version' => array('type' => 'integer', 'length' => 8), 'scheduled_update_date' => array('type' => 'date', 'length' => 25), 'done_at' => array('type' => 'timestamp', 'length' => 25)), array('indexes' => array(), 'primary' => array(0 => 'id', 1 => 'version')));
    
    $this->createForeignKey('ull_clone_user_version', array('local' => 'updator_user_id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_clone_user_version_updator_user_id'));    
    $this->createForeignKey('ull_clone_user_version', array('local' => 'id', 'foreign' => 'id', 'foreignTable' => 'ull_entity', 'onUpdate' => NULL, 'onDelete' => NULL, 'name' => 'ull_clone_user_version_id'));        
  }

  public function down()
  {
    $this->removeColumn('ull_entity', 'parent_ull_user_id');
    $this->removeColumn('ull_entity_version', 'parent_ull_user_id');
    $this->removeColumn('ull_user_version', 'parent_ull_user_id');
    $this->removeColumn('ull_group_version', 'parent_ull_user_id'); 
    $this->removeColumn('ull_ventory_origin_dummy_user_version', 'parent_ull_user_id');
    $this->removeColumn('ull_ventory_status_dummy_user_version', 'parent_ull_user_id');    
    
    $this->dropTable('ull_clone_user_version');
  }
}