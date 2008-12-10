<?php

/**
 * myModule actions.
 *
 * @package    myProject
 * @subpackage myModule
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class myModuleActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    //$this->forward('default', 'module');
  }
  
  public function executeTest()
  {
    
    $user = Doctrine::getTable('UllFlowApp')->find(1);
    $user->delete();
    
    die();
    

//    $test = Doctrine::getTable('TestTable')->find(1);
//    $test->addTag('foobar');
//    $test->addTag('mama, papa');
//    $test->save();
//    
//    
//    $t1 = Doctrine::getTable('TestTable')->find(2);
//    $t1->addTag('mama,oma');
//    $t1->save();
    
//    $q = new Doctrine_Query;
//    $q
//      ->from('TestTable')
//      ->where('my_text LIKE ?', '%ore%');
//    ;
//    
    $q = PluginTagTable::getObjectTaggedWithQuery('TestTable', 'mama, papa');
    
    
    
    $this->o = $q->execute();
  }
}
