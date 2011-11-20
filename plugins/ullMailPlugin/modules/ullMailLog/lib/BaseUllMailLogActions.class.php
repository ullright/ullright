<?php

/**
 * ullMailLog actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllMailLogActions extends BaseUllGeneratorActions
{
  
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();

    //add module stylsheet for all actions but show
    if ($this->getActionName() !== 'show')
    {
      $path =  '/ullMailTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
      $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
    }   
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->redirect('ullMailLog/list');
  }
  
  /**
   * Execute list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_log_list');
    
    $this->named_queries = new ullNamedQueriesUllMailLog();
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    $ullNewsletterEditionId = $this->getRequest()->getParameter('ull_newsletter_edition_id');
    
    if (!$ullNewsletterEditionId)
    {
      $this->redirect404();
    }    
    
    $ullNewsletterEdition = Doctrine::getTable('UllNewsletterEdition')
      ->findOneById($ullNewsletterEditionId);
      
    if (!$ullNewsletterEdition)
    {
      $this->redirect404();
    }
    
    $this->q->addWhere('ull_newsletter_edition_id = ?', $ullNewsletterEditionId);
    
    $this->setVar('ull_news_letter_edition', $ullNewsletterEdition, true);
  }   
  
  /**
   * Delete is not allowed here
   */
  public function executeDelete(sfRequest $request)
  {
    $this->redirect404();
  }
  
  
  /**
   * Execute edit action
   * 
   * @param sfWebRequest $request
   */  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_log_edit');
    
    parent::executeEdit($request);
  }
  
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullMailLogGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getEditGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullMailLogGenerator('r');
  }  
  
}