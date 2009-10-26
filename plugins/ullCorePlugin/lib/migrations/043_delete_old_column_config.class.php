<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class DeleteOldColumnConfig extends Doctrine_Migration
{
  public function up()
  {

    
    $libPath = sfConfig::get('sf_lib_dir');
    unlink($libPath . '/model/doctrine/ullCorePlugin/base/BaseUllColumnConfig.class.php');
    unlink($libPath . '/model/doctrine/ullCorePlugin/UllColumnConfig.class.php');
    unlink($libPath . '/model/doctrine/ullCorePlugin/UllColumnConfigTable.class.php');
    unlink($libPath . '/form/doctrine/ullCorePlugin/base/BaseUllColumnConfigForm.class.php');
    unlink($libPath . '/form/doctrine/ullCorePlugin/UllColumnConfigForm.class.php');
    unlink($libPath . '/filter/doctrine/ullCorePlugin/base/BaseUllColumnConfigFormFilter.class.php');
    unlink($libPath . '/filter/doctrine/ullCorePlugin/UllColumnConfigFormFilter.class.php');
    unlink($libPath . '/form/doctrine/ullCorePlugin/base/BaseUllColumnConfigTranslationForm.class.php');
    unlink($libPath . '/form/doctrine/ullCorePlugin/UllColumnConfigTranslationForm.class.php');
    unlink($libPath . '/filter/doctrine/ullCorePlugin/base/BaseUllColumnConfigTranslationFormFilter.class.php');
    unlink($libPath . '/filter/doctrine/ullCorePlugin/UllColumnConfigTranslationFormFilter.class.php');

    // We use direct pdo statements for drop table, because the model class files
    //   don't exists anymore after svn update
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("DROP TABLE ull_column_config_translation");
    $dbh->exec("DROP TABLE ull_column_config");    
    
//    try {
//      $tableConfig = Doctrine::getTable('UllTableConfig')->findOneByDbTableName('UllColumnConfig');
//      if ($tableConfig !== false)
//      {
//        $tableConfig->delete();
//      }
//    }
//    catch (Exception $exception)
//    {
//      //doesn't matter
//    }
  }
  
  public function down()
  {
    throw new Doctrine_Migration_IrreversibleMigrationException('This migration can not be undone.');
  }
}