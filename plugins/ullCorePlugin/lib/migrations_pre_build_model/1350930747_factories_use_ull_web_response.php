<?php

class factoriesUseUllWebResponse extends Doctrine_Migration_Base
{
  public function up()
  {
    // open the settings.yml file in read mode and read it
    $filename = sfConfig::get('sf_app_config_dir') . '/factories.yml';
    $file = file_get_contents($filename);
    
    $search = <<<EOF
#  response:
#    class: sfWebResponse
EOF;

    $replace = <<<EOF
  response:
    class: ullWebResponse
EOF;

    $file = str_replace($search, $replace, $file);
      
    file_put_contents($filename, $file);
  }

  public function down()
  {
  }
}
