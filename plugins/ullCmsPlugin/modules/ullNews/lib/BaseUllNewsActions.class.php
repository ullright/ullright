<?php

/**
 * Cms actions.
 * 
 * This action extends ullTableTool to add some specific functionality
 * for ullCms
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllNewsActions extends BaseUllGeneratorActions
{  
  
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    //Add ullCms stylsheet for all actions
    $path =  '/ullCmsTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }    
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_news_list');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_news_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonNewsSaveAndShow($this));
    
    parent::executeEdit($request);

    $this->setTableToolTemplate('edit');
  }
  
 /* public function executeShow(sfRequest $request) 
  {
  if (!$this->hasRequestParameter('slug') and !$this->hasRequestParameter('id'))
    {
      throw new InvalidArgumentException('At least one of the "slug" or "id" parameters have to be given');
    }

    if ($docId = $this->getRequestParameter('slug'))
    {
      $this->doc = Doctrine::getTable('UllNews')->findOneBySlug($docId);
      $this->forward404Unless($this->doc);
    }
    elseif ($docId = $this->getRequestParameter('id'))
    {
      $this->doc = Doctrine::getTable('UllNews')->findOneById($docId);
      $this->forward404Unless($this->doc);
    }
  }*/

  public function executeNewsList(sfRequest $request)
  {
    $this->checkPermission('ull_news_newsList');
  }
  
  public function executeNewsListFeed(sfRequest $request)
  {
    $this->checkPermission('ull_news_newsList');
    
    $this->lang = $request->getParameter('lang', sfConfig::get('sf_default_culture'));
    if (!in_array($this->lang, sfConfig::get('app_i18n_supported_languages'))) {
      $this->redirect404('Invalid language: ' . $this->lang);
    }   
    $this->getUser()->setCulture($this->lang);
    $this->getResponse()->setContentType('text/xml');
    $this->setLayout(false);
    $this->newsEntries = Doctrine::getTable('UllNews')->findLatestActiveNews();
  }
  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullFilterForm';
  }  
  
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullNewsGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullNewsGenerator('w');
  }    
  
}
