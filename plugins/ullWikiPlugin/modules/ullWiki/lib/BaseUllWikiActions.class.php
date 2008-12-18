<?php
/**
 * ullWiki actions
 *
 * @package    ullright
 * @subpackage ullWiki
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */

class BaseUllWikiActions extends ullsfActions
{
  /**
   * Add ullWiki stylsheet for all actions
   * 
   */
  public function ullpreExecute()
  { 
    $path =  '/ullWikiTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
  	$this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }
	
  /**
   * Execute index action
   * 
   */
  public function executeIndex() 
  {
    $this->form = new ullWikiFilterForm;

    // referer handling -> reset all wiki referers 
    $refererHandler = new refererHandler();
    $refererHandler->delete('show');
    $refererHandler->delete('edit');

    // allow ullwiki to be used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');

    $this->breadcrumbForIndex();
  }

  /**
   * Execute list action
   * 
   */
  public function executeList() 
  {

    // referer handling -> reset all wiki referers
    $refererHandler = new refererHandler();
    $refererHandler->delete('show');
    $refererHandler->delete('edit');


    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');

    //$this->ull_reqpass_redirect(); //ToDo

    $this->breadcrumbForList();
    
    $this->generator = new ullWikiGenerator();

    $this->docs = $this->getFilterFromRequest();
    
    $this->generator->buildForm($this->docs);
    
  }


  /**
   * Execute Show action
   * 
   */
  public function executeShow() 
  {
    // referer handling -> reset show referer
    $this->refererHandler = new refererHandler();
    $this->refererHandler = new refererHandler();
    $this->refererHandler->initialize('show');
    
    $this->getDocFromRequest();

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    if ($this->return_var = $this->getRequestParameter('return_var')) 
    {
       $this->return_url = $this->getUser()->getAttribute('wiki_return_url') 
        . '&' . $this->return_var . '=' . $this->doc->id;
    }

    $this->breadcrumbForShow();
  }

  /**
   * Execute create action
   * 
   */
  public function executeCreate() 
  {
    $this->forward('ullWiki', 'edit');
  }

  /**
   * Execute edit action
   * 
   */
  public function executeEdit($request) 
  {
    $this->checkAccess('MasterAdmins');
    
    $this->refererHandler = new refererHandler();
    $this->refererHandler->initialize();
    
    $this->breadcrumbForEdit();
    
    $this->generator = new ullWikiGenerator('w');
    $this->getDocFromRequestOrCreate();
    $this->generator->buildForm($this->doc);
    
    // allows ullWiki to be used as a plugin (e.g. ullFlow to ullWiki interface)
    $this->return_var = $this->getRequestParameter('return_var');    

    if ($request->isMethod('post'))
    {
//      var_dump($this->getRequest()->getParameterHolder()->getAll());die;
      
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        // == forward junction
        if ($this->getRequestParameter('submit_save_only', false)) 
        {
          return $this->redirect('ullWiki/edit?docid='.$this->doc->id);

        // plugin mode
        } 
        elseif ($this->return_var) 
        {
          // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
          $returnUrl = $this->getUser()->getAttribute('wiki_return_url') 
              . '&' . $this->return_var . '=' . $this->doc->id;
          
          return $this->redirect($returnUrl);
        } 
        elseif ($this->getRequestParameter('submit_save_show', false)) 
        {
          return $this->redirect('ullWiki/show?docid='.$this->doc->id);
        } 
        else 
        {
          $refererHandler = new refererHandler();

          // skip returning to the show action -> jump directly to the pervious result list
          if ($refererHandler->hasReferer('show')) 
          {
            $refererHandler->delete('edit');
            $return = $this->redirect($refererHandler->getRefererAndDelete('show'));
          } 
          else 
          {
            $return = $this->redirect($refererHandler->getRefererAndDelete('edit'));
          }

          return $return;
        }
      }

    }
  }

  /**
   * Execute delete action
   */
  public function executeDelete() 
  {
    // check access
    $this->checkAccess('MasterAdmins');

    $this->getDocFromRequest();
    
    $this->doc->delete();
    
    
    return $this->redirect('ullWiki/list');
  }

  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');
  }

  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');
    $this->breadcrumbTree->add(__('Result list', null, 'common'), 'ullWiki/list');
  }  

  /**
   * Create breadcrumbs for show action
   * 
   */  
  protected function breadcrumbForShow() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');

    // display result list link only when there is a "show" or "edit" referer containing 
    //  the list action    
    if ( strstr($this->refererHandler->getReferer('show'), 'ullWiki/list')
      or strstr($this->refererHandler->getReferer('edit'), 'ullWiki/list')) 
    {
      $this->breadcrumbTree->add(
        __('Result list', null, 'common'),
        $this->refererHandler->getReferer()
      );
    }

    $this->breadcrumbTree->add(__('Show', null, 'common'));    
    $this->breadcrumbTree->add($this->doc->subject);
  }  

  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');

    // display result list link only when there is a "show" or "edit" referer 
    //  containing the list action    
    if (strstr($this->refererHandler->getReferer('show'), 'ullWiki/list')
      or strstr($this->refererHandler->getReferer('edit'), 'ullWiki/list')) 
    {
      $this->breadcrumbTree->add(
        __('Result list', null, 'common'),
        $this->refererHandler->getReferer()
      );
    }

    // display breadcrumb show link only when there is an "edit" referer 
    //  containing the show action 
    if (strstr($this->refererHandler->getReferer('edit'), 'ullWiki/show')) 
    {
      $this->breadcrumbTree->add(__('Result list', null, 'common'),
        $this->refererHandler->getReferer());
    }

    $this->breadcrumbTree->add(__('Edit', null, 'common'));
  }

  /**
   * Parses filter request params and gets a collection of UllWiki docs
   * 
   */
  protected function getFilterFromRequest()
  {

    $this->filter_form = new ullWikiFilterForm;
    $this->filter_form->bind($this->getRequestParameter('filter'));

    // build query
    // Querying for records excludes deleted records.
    $q = new Doctrine_Query();
    $q->from('UllWiki x');

    // search has to be the first "where" part, because it uses "or" 
    if ($search = $this->filter_form->getValue('search'))
    {
      $cols = array('id', 'subject', 'duplicate_tags_for_search');
      if ($this->filter_form->getValue('fulltext'))
      {
        $cols[] = 'body';
      }
      $q = ullCoreTools::doctrineSearch($q, $search, $cols);
    }
    
    // TODO: (Klemens 2008-12-16) this should not be necessary,
    //   because the soft delete behaviour handles this. But the
    //   softdelete functionality needs the doctrine attribute
    //   "use_dql_callbacks" enabled, which is not at the moment
    //   because it has sideeffects and needs further testing
    $q->addWhere('x.deleted = ?', false);
    
    $this->order = $this->getRequestParameter('order', 'created_at');
    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');
    
    $orderDir = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';

    switch ($this->order)
    {
      case 'creator_user_id':
        $q->orderBy('x.Creator.display_name ' . $orderDir);
        break;
      case 'updator_user_id':
        $q->orderBy('x.Updator.display_name ' . $orderDir);
        break;
      default:
        $q->orderBy($this->order . ' ' . $orderDir);
    }
    
    $this->pager = new Doctrine_Pager(
      $q, 
      $this->getRequestParameter('page', 1),
      sfConfig::get('app_pager_max_per_page')
    );
    $docs = $this->pager->execute();

    return ($docs->count()) ? $docs : new UllWiki;
  }

  /**
   * Gets UllWiki doc according to request param
   * 
   */
  protected function getDocFromRequest()
  {
    $this->forward404Unless($this->getRequestParameter('docid'), 'docid is mandatory!');

    $this->getDocFromRequestOrCreate();
  }
  
  /**
   * Gets UllWiki doc or creates it according to request param
   * 
   */
  protected function getDocFromRequestOrCreate()
  {
    if ($id = $this->getRequestParameter('docid')) 
    {
      $this->doc = Doctrine::getTable('UllWiki')->find($id);
      $this->forward404Unless($this->doc);
    }
    else
    {
      $this->doc = new UllWiki;
    }
  }

}