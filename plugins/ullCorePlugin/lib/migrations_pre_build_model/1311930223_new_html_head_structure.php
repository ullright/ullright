<?php

class NewHtmlHeadStructure extends Doctrine_Migration_Base
{
  public function up()
  {

    $globalTemplatePath = sfConfig::get('sf_app_template_dir');
    
    $files = array(
      '_html_head.php',
      '_html_head.mobile.php'
    );
    
    foreach($files as $file)
    {
      if (!file_exists($globalTemplatePath . '/' . $file))
      {
        shell_exec(
          'svn export http://bigfish.ull.at/svn/ullright/trunk/apps/frontend/templates/' .
          $file .
          ' apps/frontend/templates/' .
          $file
        );
      }
    }
    
  }
  public function down()
  {
  }
}
