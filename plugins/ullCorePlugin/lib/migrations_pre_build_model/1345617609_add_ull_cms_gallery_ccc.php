<?php

class AddUllCmsGalleryCCC extends Doctrine_Migration_Base
{
  public function up()
  {

    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    
    // Add a column if it does not exist yet:
    try
    {
      $result = $dbh->query("SELECT gallery FROM ull_cms_item LIMIT 1");
    }
    catch (Exception $e)
    {
       $this->disableGalleryInColumnsConfig();
    }
    
  }
  
  protected function disableGalleryInColumnsConfig()
  {
    $file = 'apps/frontend/lib/generator/columnConfigCollection/' .
      'UllCmsItemColumnConfigCollection.class.php';
    
    if (!file_exists($file))
    {
      var_dump(ullBaseTask::svnExportFromUllright($file));
    }
    
    $content = file_get_contents($file);
    
    $content = str_replace('parent::applyCustomSettings();', 
        "parent::applyCustomSettings();\n" .
          "\n" .
          '    $this->disable(array(\'gallery\'));' . "\n" .
          "\n"
        , 
        $content
    );
    
    file_put_contents($file, $content);    
  }
  
  
  public function postUp()
  {
  } 

  public function down()
  {
    
  }
}
