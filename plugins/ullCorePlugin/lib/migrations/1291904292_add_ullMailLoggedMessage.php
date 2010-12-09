<?php

class AddUllMailLoggedMessage extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->createTable('ull_mail_logged_message', array(
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
             'sender' => 
    array(
              'type' => 'string',
              'notnull' => true,
              'length' => 500,
    ),
             'main_recipient_ull_user_id' => 
    array(
              'type' => 'integer',
              'length' => 8,
    ),
             'to_list' => 
    array(
              'type' => 'clob',
              'notnull' => true,
              'length' => NULL,
    ),
             'cc_list' => 
    array(
              'type' => 'clob',
              'length' => NULL,
    ),
             'bcc_list' => 
    array(
              'type' => 'clob',
              'length' => NULL,
    ),
             'headers' => 
    array(
              'type' => 'clob',
              'length' => NULL,
    ),
             'subject' => 
    array(
              'type' => 'string',
              'notnull' => true,
              'length' => 255,
    ),
             'plaintext_body' => 
    array(
              'type' => 'clob',
              'length' => NULL,
    ),
             'html_body' => 
    array(
              'type' => 'clob',
              'length' => NULL,
    ),
             'transport_sent_status' => 
    array(
              'type' => 'boolean',
              'length' => 25,
    ),
             'sent_at' => 
    array(
              'type' => 'timestamp',
              'length' => 25,
    ),
             'first_read_at' => 
    array(
              'type' => 'timestamp',
              'length' => 25,
    ),
             'num_of_readings' => 
    array(
              'type' => 'integer',
              'default' => 0,
              'notnull' => true,
              'length' => 8,
    ),
             'last_ip' => 
    array(
              'type' => 'string',
              'length' => 15,
    ),
             'last_user_agent' => 
    array(
              'type' => 'string',
              'length' => 500,
    ),
             'ull_newsletter_edition_id' => 
    array(
              'type' => 'integer',
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
    $this->createForeignKey('ull_mail_logged_message', 'ull_mail_logged_message_creator_user_id_ull_entity_id', array(
             'name' => 'ull_mail_logged_message_creator_user_id_ull_entity_id',
             'local' => 'creator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
    ));
    $this->createForeignKey('ull_mail_logged_message', 'ull_mail_logged_message_updator_user_id_ull_entity_id', array(
             'name' => 'ull_mail_logged_message_updator_user_id_ull_entity_id',
             'local' => 'updator_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
    ));
    $this->createForeignKey('ull_mail_logged_message', 'uuui_1', array(
             'name' => 'uuui_1',
             'local' => 'ull_newsletter_edition_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_newsletter_edition',
    ));
    $this->createForeignKey('ull_mail_logged_message', 'ull_mail_logged_message_main_recipient_ull_user_id_ull_entity_id', array(
             'name' => 'ull_mail_logged_message_main_recipient_ull_user_id_ull_entity_id',
             'local' => 'main_recipient_ull_user_id',
             'foreign' => 'id',
             'foreignTable' => 'ull_entity',
    ));
  }

  public function down()
  {
    $this->dropForeignKey('ull_mail_logged_message', 'ull_mail_logged_message_creator_user_id_ull_entity_id');
    $this->dropForeignKey('ull_mail_logged_message', 'ull_mail_logged_message_updator_user_id_ull_entity_id');
    $this->dropForeignKey('ull_mail_logged_message', 'uuui_1');
    $this->dropForeignKey('ull_mail_logged_message', 'ull_mail_logged_message_main_recipient_ull_user_id_ull_entity_id');
     
    $this->dropTable('ull_mail_logged_message');
  }
}