<?php

class RenameCmsAdminGroup extends Doctrine_Migration_Base
{
  public function up()
  {
    $q = new Doctrine_Query;
    $q
      ->update('UllEntity e')
      ->set('display_name', '?', 'CmsAdmins')
      ->where('display_name = ?', 'cmsAdmins')
    ;
    $q->execute();    
  }

  public function down()
  {
  }
}
