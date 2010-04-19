<?php

class AddUllCommentablePermission extends Doctrine_Migration_Base
{
  public function up()
  {
    $permission = new UllPermission;
    $permission->slug = 'ull_commentable_revoke_comments';
    $permission->namespace = 'ullCore';
    $permission->save();  
  }

  public function down()
  {
    Doctrine::getTable("UllPermission")->findOneBySlug('ull_commentable_revoke_comments')->delete();
  }
}
