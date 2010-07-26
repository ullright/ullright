<?php

class AddUllBooking extends Doctrine_Migration_Base
{
  public function up()
  {
    try
    {
      $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
      $dbh->query('select id from ull_booking_resource');
    }
    catch (PDOException $e)
    {
      //add table for UllBookingResource
      $this->createTable('ull_booking_resource', array(
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
             'is_bookable' => 
             array(
              'type' => 'boolean',
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
    
    try
    {
      $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
      $dbh->query('select id from ull_booking');
    }
    catch (PDOException $e)
    {
      //add table for UllBooking
      $this->createTable('ull_booking', array(
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
               'start' => 
               array(
                'type' => 'datetime',
                'notnull' => true,
                'length' => NULL,
               ),
               'end' => 
               array(
                'type' => 'datetime',
                'notnull' => true,
                'length' => NULL,
               ),
               'ull_booking_resource_id' => 
               array(
                'type' => 'integer',
                'notnull' => true,
                'length' => 8,
               ),
               'booking_group_name' => 
               array(
                'type' => 'string',
                'length' => 23,
               ),
               'name' => 
               array(
                'type' => 'string',
                'notnull' => true,
                'length' => 50,
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
               
      //and translations
      $this->createTable('ull_booking_resource_translation', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'primary' => true,
             ),
             'name' => 
             array(
              'type' => 'string',
              'length' => 100,
             ),
             'lang' => 
             array(
              'fixed' => true,
              'primary' => true,
              'type' => 'string',
              'length' => 2,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
              1 => 'lang',
             ),
             ));
    }
 
    //add BookingAdmins group (if not exists)
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("select id from ull_entity where type = 'group' and display_name = 'BookingAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if ($row)
    {
      $groupId = $row['id'];
    }
    else
    {
      
      $bookingAdmins = new UllGroup();
      $bookingAdmins->display_name = 'BookingAdmins';
      $bookingAdmins->namespace = 'ull_booking';
      $bookingAdmins->save();
      $groupId = $bookingAdmins->id;
    }
    
    //drop permissions ...
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("delete from ull_group_permission_version where namespace = 'ull_booking'");
    $dbh->exec("delete from ull_group_permission where namespace = 'ull_booking'");
    $dbh->exec("delete from ull_permission_version where namespace = 'ull_booking'");
    $dbh->exec("delete from ull_permission where namespace = 'ull_booking'");
    
    //and recreate
    foreach (array('index', 'schedule', 'create', 'delete', 'edit') as $action)
    {
      $p = new UllPermission;
      $p->slug = 'ull_booking_' . $action;
      $p->namespace = 'ull_booking';
      $p->save();
      
      $gp = new UllGroupPermission;
      $gp->ull_group_id = $groupId;
      $gp->UllPermission = $p;
      $gp->namespace = 'ull_booking';
      $gp->save();
    }
  }

  public function postUp()
  {
    $this->createForeignKey('ull_booking', 'ull_booking_creator_user_id_ull_entity_id', array(
             'name' => 'ull_booking_creator_user_id_ull_entity_id',
             'local' => 'creator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             ));
    $this->createForeignKey('ull_booking', 'ull_booking_updator_user_id_ull_entity_id', array(
             'name' => 'ull_booking_updator_user_id_ull_entity_id',
             'local' => 'updator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             ));
    $this->createForeignKey('ull_booking', 'ull_booking_ull_booking_resource_id_ull_booking_resource_id', array(
             'name' => 'ull_booking_ull_booking_resource_id_ull_booking_resource_id',
             'local' => 'ull_booking_resource_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_booking_resource',
             ));
    $this->createForeignKey('ull_booking_resource', 'ull_booking_resource_creator_user_id_ull_entity_id', array(
             'name' => 'ull_booking_resource_creator_user_id_ull_entity_id',
             'local' => 'creator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             ));
    $this->createForeignKey('ull_booking_resource', 'ull_booking_resource_updator_user_id_ull_entity_id', array(
             'name' => 'ull_booking_resource_updator_user_id_ull_entity_id',
             'local' => 'updator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
             ));
    $this->createForeignKey('ull_booking_resource_translation', 'ull_booking_resource_translation_id_ull_booking_resource_id', array(
             'name' => 'ull_booking_resource_translation_id_ull_booking_resource_id',
             'local' => 'id',
             'foreign' => 'id',
             'foreignTable' => 'ull_booking_resource',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
