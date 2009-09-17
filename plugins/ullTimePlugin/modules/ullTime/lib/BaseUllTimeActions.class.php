<?php

/**
 * ullTime actions.
 *
 * @package    ullright
 * @subpackage ullTime
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllTimeActions extends ullsfActions
{
 
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list';
    $this->getUriMemory()->setDefault($defaultUri);  
    
    //Add ullTime stylsheet for all actions
    $path =  '/ullTimeTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex() 
  {
//    $this->checkPermission('ull_ventory_index');
    
//    $this->form = new ullVentoryFilterForm;
    
//    $this->named_queries = new ullNamedQueriesUllVentory;

//    $this->breadcrumbForIndex();
  }

}