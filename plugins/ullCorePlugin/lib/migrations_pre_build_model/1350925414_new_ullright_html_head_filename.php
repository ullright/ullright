<?php

class newUllrightHtmlHeadFilenameMigration extends Doctrine_Migration_Base
{
  public function up()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_dir') . '/templates/_html_head.php';
    $file = file_get_contents($filename);
    
    $search = <<<EOF
<?php /* Load default ullright html head */ ?>
<?php include_partial('default/html_head')?>
EOF;

    $replace = <<<EOF
<?php /* Load ullright html head statements*/ ?>
<?php /* Located in plugins/ullCorePlugin/modules/default/templates/_ullright_html_head.php */ ?>
<?php include_partial('default/ullright_html_head')?>
EOF;

    $file = str_replace($search, $replace, $file);
      
    file_put_contents($filename, $file);
  }

  public function down()
  {
  }
}
