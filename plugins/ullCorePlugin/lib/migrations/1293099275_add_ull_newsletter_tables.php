<?php

class AddUllNewsletterTables extends Doctrine_Migration_Base
{
  public function up()
  {
    
            $this->createTable('ull_newsletter_layout', array(
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
             'name' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 200,
             ),
             'html_layout' => 
             array(
              'type' => 'clob',
              'notnull' => true,
              'length' => NULL,
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
             'slug' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             ), array(
             'indexes' => 
             array(
              'ull_newsletter_layout_sluggable' => 
              array(
              'fields' => 
              array(
               0 => 'slug',
              ),
              'type' => 'unique',
              ),
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
             
             
        $this->createTable('ull_newsletter_mailing_list_subscriber', array(
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
             'ull_newsletter_mailing_list_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'ull_user_id' => 
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

             
          $this->createTable('ull_newsletter_mailing_list', array(
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
             'name' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 200,
             ),
             'description' => 
             array(
              'type' => 'string',
              'length' => 3000,
             ),
             'is_active' => 
             array(
              'type' => 'boolean',
              'default' => 1,
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
             'slug' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             ), array(
             'indexes' => 
             array(
              'ull_newsletter_mailing_list_sluggable' => 
              array(
              'fields' => 
              array(
               0 => 'slug',
              ),
              'type' => 'unique',
              ),
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));

    
    
            $this->createTable('ull_newsletter_edition_mailing_list', array(
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
             'ull_newsletter_edition_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'ull_newsletter_mailing_list_id' => 
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
             
             
        $this->createTable('ull_newsletter_edition', array(
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
             'subject' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'length' => 255,
             ),
             'ull_newsletter_layout_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'body' => 
             array(
              'type' => 'clob',
              'notnull' => true,
              'length' => NULL,
             ),
             'sent_at' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'sent_by_ull_user_id' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'sender_culture' => 
             array(
              'type' => 'string',
              'length' => 5,
             ),
             'queued_at' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'is_active' => 
             array(
              'type' => 'boolean',
              'default' => 1,
              'length' => 25,
             ),
             'num_sent_emails' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'num_failed_emails' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'num_read_emails' => 
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

  public function down()
  {
  }
}
