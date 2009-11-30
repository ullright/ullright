<?php
class AddUllUserAdminAccessCheck extends Doctrine_Migration
{
  public function up()
  {
    $tables = array(
      'UllCloneUser',
      'UllCompany',
      'UllLocation',
      'UllDepartment',
      'UllJobTitle',
      'UllEmploymentType',
    );
    
    foreach($tables as $table)
    {
      $permission = 'ull_tabletool_' . sfInflector::underscore($table);
      
      $this->addUllUserPermission($permission);
    }
    
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
  
  
  protected function addUllUserPermission($slug)
  {
    $p = new UllPermission;
    $p->slug = $slug;
    $p->namespace = 'ullCore';
    $p->save();    
    
    $gp = new UllGroupPermission;
    
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'UserAdmins'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $gp->ull_group_id = $row['id'];
    
    $gp->UllPermission = $p;
    $gp->namespace = 'ullCore';
    $gp->save();  
  }
}