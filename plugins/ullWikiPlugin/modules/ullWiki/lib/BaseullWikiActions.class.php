<?php
/**
 * ullWiki actions
 *
 * @package    ullright
 * @subpackage ullWiki
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */

class BaseullWikiActions extends ullsfActions
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

    $this->docs = $this->getFilterFromRequest();
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
        . '&' . $this->return_var . '=' . $this->ullwiki->getDocId(); ;
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
  public function executeEdit($request) {

    $this->checkAccess('MasterAdmins');
    
    $this->refererHandler = new refererHandler();
    $this->refererHandler->initialize();
    
    $this->getDocFromRequestOrCreate();
    $this->form = new UllWikiForm($this->doc);

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');    

    if ($request->isMethod('post'))
    {
      if ($this->form->bindAndSave($request->getParameter('ull_wiki')))
      {
        // == forward junction
        if ($this->getRequestParameter('submit_save_only', false)) 
        {
          return $this->redirect('ullWiki/edit?docid='.$this->doc->id);

        // plugin mode
        } 
        elseif (isset($return_url)) 
        {
            // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
          if ($return_var = $this->getRequestParameter('return_var')) 
          {
             $return_url = $this->getUser()->getAttribute('wiki_return_url') 
              . '&' . $return_var . '=' . $this->doc->id;
          }          
          
          return $this->redirect($return_url);
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
    else
    {
      $this->breadcrumbForEdit();
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
    $this->breadcrumbTree->addFinal(__('Wiki'));
  }

  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');
    $this->breadcrumbTree->addFinal(__('Result list', null, 'common'));
  }  

  /**
   * Create breadcrumbs for show action
   * 
   */  
  protected function breadcrumbForShow() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');

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
    $this->breadcrumbTree->addFinal($this->doc->subject);
  }  

  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');

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
    $q->from('UllWiki w')
      ->where('deleted = ?', 0)
    ;

    if ($this->getRequestParameter('sort')) 
    {
      $q->orderBy('w.'.$this->getRequestParameter('sort').' ASC');
    } 
    else 
    {
      $q->orderBy('w.updated_at DESC');
    }

    if ($search = $this->filter_form->getValue('search')) 
    {
      $fulltext = $this->filter_form->getValue('fulltext');

      $query_subject = '';
      $query_tags = '';
      $query_body = '';

      $search_words_arr = explode(' ', $search);
      foreach($search_words_arr as $key => $search_word) {
        $search_word = '"%'.$search_word.'%"';

        $query_subject .= ($query_subject != '' ? ' AND ':'') . 'w.subject LIKE '.$search_word;
        $query_tags    .= ($query_tags!=''?' AND ':'')    . 'w.duplicate_tags_for_search LIKE '.$search_word;

        if ($fulltext) {
          $query_body  .= ($query_body!=''?' AND ':'')    . 'w.body LIKE '.$search_word;
        }
      }

      $q->addWhere($query_subject . ' OR ' . $query_tags . ($query_body!=''?' OR ':'') . $query_body);
    }

    $this->pager = new Doctrine_Pager(
      $q, 
      $this->getRequestParameter('page', 1),
      sfConfig::get('app_pager_max_per_page')
    );
    $docs = $this->pager->execute();

    return ($docs->count()) ? $docs : false;

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