<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUllWiki extends UllRecord
{
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->setTableName('ull_wiki');
    $this->hasColumn('docid', 'integer', null, array('type' => 'integer', 'notnull' => true));
    $this->hasColumn('current', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('culture', 'string', 7, array('type' => 'string', 'length' => '7'));
    $this->hasColumn('body', 'clob', null, array('type' => 'clob'));
    $this->hasColumn('subject', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('changelog_comment', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('read_counter', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('edit_counter', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('duplicate_tags_for_search', 'clob', null, array('type' => 'clob'));
    $this->hasColumn('locked_by_user_id', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('locked_at', 'timestamp', null, array('type' => 'timestamp'));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('UllUser', array('local' => 'locked_by_user_id',
                                   'foreign' => 'id'));
  }
}