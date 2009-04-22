<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SetDefaultWikiAccessLevel extends Doctrine_Migration
{
  public function up()
  {    
    // using doctrine query "update" because it doesn't trigger the behaviours, 
    // so e.g. the updated_at column isn't set 
    $q = new Doctrine_Query();
    
    $q
      ->update('UllWiki w')
      ->set('w.ull_wiki_access_level_id', Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('public_readable')->id)
    ;
    
    $q->execute();    
  }
    
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}