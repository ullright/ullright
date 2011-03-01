<?php

class AddUllNewsletterAccess extends Doctrine_Migration_Base
{
  public function up()
  {
  }

  public function postUp()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    $result = $dbh->query("INSERT INTO ull_entity (type, display_name, namespace) 
        VALUES ('group', 'NewsletterAdmins', 'ull_newsletter')");
    $groupId = $dbh->lastInsertId();

    $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'Everyone'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $everyoneGroupId = $row['id'];

    foreach (array('ull_newsletter_index', 'ull_newsletter_show',
    	'ull_newsletter_list') as $permissionName)
    {
      //if the $permissionName permission does not exist, create it
      //and assign it to the group Everyone (= also unlogged user)
      $result = $dbh->query("SELECT * FROM ull_permission WHERE slug='" . $permissionName . "'");

      if (!$result->fetch(PDO::FETCH_ASSOC))
      {
        $p = new UllPermission;
        $p->slug = $permissionName;
        $p->namespace = 'ull_newsletter';
        $p->save();

        $gp = new UllGroupPermission;
        $gp->ull_group_id = $everyoneGroupId;
        $gp->UllPermission = $p;
        $gp->namespace = 'ull_newsletter';
        $gp->save();
      }
    }

    $permissionName = 'ull_newsletter_edit';

    $result = $dbh->query("SELECT * FROM ull_permission WHERE slug='" . $permissionName . "'");

    if (!$result->fetch(PDO::FETCH_ASSOC))
    {
      $result = $dbh->query("SELECT id FROM ull_entity WHERE type='group' AND display_name = 'NewsletterAdmins'");
      $row = $result->fetch(PDO::FETCH_ASSOC);
      $newsletterAdminsGroupId = $row['id'];

      $p = new UllPermission;
      $p->slug = $permissionName;
      $p->namespace = 'ull_newsletter';
      $p->save();

      $gp = new UllGroupPermission;
      $gp->ull_group_id = $newsletterAdminsGroupId;
      $gp->UllPermission = $p;
      $gp->namespace = 'ull_newsletter';
      $gp->save();
    }
  }

  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}
