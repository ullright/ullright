<?php

class AddUllEntityVersion extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ull_entity_version', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'primary' => true,
             ),
             'namespace' => 
             array(
              'type' => 'string',
              'length' => 32,
             ),
             'display_name' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'email' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'username' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'ull_location_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'type' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             'first_name' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'last_name' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'password' => 
             array(
              'type' => 'string',
              'length' => 40,
             ),
             'last_name_first' => 
             array(
              'type' => 'string',
              'length' => 129,
             ),
             'sex' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => NULL,
              1 => 'm',
              2 => 'f',
              ),
              'length' => NULL,
             ),
             'entry_date' => 
             array(
              'type' => 'date',
              'length' => 25,
             ),
             'deactivation_date' => 
             array(
              'type' => 'date',
              'length' => 25,
             ),
             'separation_date' => 
             array(
              'type' => 'date',
              'length' => 25,
             ),
             'is_show_in_phonebook' => 
             array(
              'type' => 'boolean',
              'default' => 1,
              'length' => 25,
             ),
             'phone_extension' => 
             array(
              'type' => 'integer',
              'length' => 20,
             ),
             'alternative_phone_extension' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'is_show_extension_in_phonebook' => 
             array(
              'type' => 'boolean',
              'default' => 1,
              'length' => 25,
             ),
             'fax_extension' => 
             array(
              'type' => 'integer',
              'length' => 20,
             ),
             'mobile_number' => 
             array(
              'type' => 'string',
              'length' => 20,
             ),
             'is_show_mobile_number_in_phonebook' => 
             array(
              'type' => 'boolean',
              'default' => 1,
              'length' => 25,
             ),
             'personnel_number' => 
             array(
              'type' => 'string',
              'length' => 20,
             ),
             'ull_employment_type_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'ull_job_title_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'ull_company_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'ull_department_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'cost_center' => 
             array(
              'type' => 'string',
              'length' => 64,
             ),
             'superior_ull_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'comment' => 
             array(
              'type' => 'string',
              'length' => 4000,
             ),
             'ull_user_status_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'default' => 1,
              'length' => 8,
             ),
             'photo' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             'is_photo_public' => 
             array(
              'type' => 'boolean',
              'default' => 1,
              'length' => 25,
             ),
             'parent_ull_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'is_virtual_group' => 
             array(
              'type' => 'boolean',
              'default' => 0,
              'length' => 25,
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
             'version' => 
             array(
              'primary' => true,
              'type' => 'integer',
              'length' => 8,
             ),
             'reference_version' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'scheduled_update_date' => 
             array(
              'type' => 'date',
              'length' => 25,
             ),
             'done_at' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
              1 => 'version',
             ),
             ));
    }

    public function postUp()
    {
      $this->createForeignKey('ull_entity_version', 'ull_entity_version_id_ull_entity_id', array(
             'name' => 'ull_entity_version_id_ull_entity_id',
             'local' => 'id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
      ));
      
      $this->createForeignKey('ull_entity_version', 'ull_entity_version_updator_user_id_ull_entity_id', array(
             'name' => 'ull_entity_version_updator_user_id_ull_entity_id',
             'local' => 'updator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
      ));  
    }
    
    public function down()
    {
        $this->dropTable('ull_entity_version');
    }
    
    public function postDown()
    {
        $this->dropForeignKey('ull_entity_version', 'ull_entity_version_id_ull_entity_id');
        $this->dropForeignKey('ull_entity_version', 'ull_entity_version_updator_user_id_ull_entity_id');
    }
}