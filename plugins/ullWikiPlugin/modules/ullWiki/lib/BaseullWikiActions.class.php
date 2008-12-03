<?php

/**
 * wiki actions.
 *
 * @package    ull_at
 * @subpackage wiki
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */

/*
 * Base ullWiki Class containing all generic actions
 */

class BaseullWikiActions extends ullsfActions
{
  public function ullpreExecute()
  { 
    $path =  '/ullWikiTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
  	$this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }
	
  public function executeIndex() 
  {
    $this->form = new ullWikiFilterForm;

    // referer handling -> reset all wiki referers 
    $refererHandler = new refererHandler();
    $refererHandler->delete('show');
    $refererHandler->delete('edit');

    // == handle request params

    // allow ullwiki to be used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');

    $this->breadcrumbForIndex();
  }



  public function executeList() {

    // referer handling -> reset all wiki referers
    $refererHandler = new refererHandler();
    $refererHandler->delete('show');
    $refererHandler->delete('edit');


    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
//    $this->return_url = $this->getRequestParameter('return_url');
    $this->return_var = $this->getRequestParameter('return_var');


    //$this->ull_reqpass_redirect(); //ToDo

    $this->breadcrumbForList();

    $this->docs = $this->getFilterFromRequest();
  }



  public function executeShow() {

    // referer handling -> reset show referer
    $this->refererHandler = new refererHandler();
    $this->refererHandler = new refererHandler();
    $this->refererHandler->initialize('show');

    // == handle request params


    // get document
    $this->ullwiki = UllWikiTable::findByDocid($this->getRequestParameter('docid'));
    $this->forward404Unless($this->ullwiki);

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    if ($this->return_var = $this->getRequestParameter('return_var')) {
       $this->return_url = $this->getUser()->getAttribute('wiki_return_url') 
        . '&' . $this->return_var . '=' . $this->ullwiki->getDocId(); ;
    }


    $this->breadcrumbForShow();

    $this->forward404Unless($this->ullwiki);
  }

  public function executeCreate() {
    $this->forward('ullWiki', 'edit');
  }


  public function executeEdit($request) {

    $this->refererHandler = new refererHandler();
    $this->refererHandler->initialize();

    // check access
    $this->checkAccess('MasterAdmins');


    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
//    $this->return_url = $this->getRequestParameter('return_url');
    $this->return_var = $this->getRequestParameter('return_var');    

    $this->breadcrumbForEdit();

    //update
    if ($request->isMethod('post'))
    {

      // check if this is a new or existing wiki article
      if ($docid = $this->getRequestParameter('docid')) {
        $this->ullwiki = UllWikiTable::findByDocid($docid);
        $this->ullwiki->setCreatorUserIdAndCreatedAt($this->getWikiFromRequest()); // keep creator and createdate
        //UllWikiTable::setOldDocsDeleted($docid);
      } else {
        $this->ullwiki = new UllWiki();
        $docid = UllWikiTable::getNextFreeDocid();
        $this->ullwiki->setDocid($docid);
        $this->ullwiki->setCreatorUserId($this->getUser()->getAttribute('user_id'));
      }

      // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
      if ($return_var = $this->getRequestParameter('return_var')) {
         $return_url = $this->getUser()->getAttribute('wiki_return_url') 
          . '&' . $return_var . '=' . $docid;
      }


      $this->form = new ullWikiForm($this->ullwiki);
      $this->form->bind($request->getParameter('ull_wiki'));

      if ($this->form->isValid()) {

        $this->ullwiki = $this->form->save();

        $tags = explode(',', strtolower($this->getRequestParameter('tags')));
        foreach ($tags as $tag) {
          $this->ullwiki->addTag(trim($tag));
        }
        $this->ullwiki->setDuplicateTagsForSearch(strtolower($this->getRequestParameter('tags')));

        $this->ullwiki->save();

        // == forward junction
        if ($this->getRequestParameter('submit_save_only', false)) {
          return $this->redirect('ullWiki/edit?docid='.$docid);

        // plugin mode
        } elseif (isset($return_url)) {
          return $this->redirect($return_url);

        } elseif ($this->getRequestParameter('submit_save_show', false)) {
          return $this->redirect('ullWiki/show?docid='.$docid);

        } else {
          $refererHandler = new refererHandler();

          // skip returning to the show action -> jump directly to the pervious result list
          if ($refererHandler->hasReferer('show')) {
            $refererHandler->delete('edit');
            $return = $this->redirect($refererHandler->getRefererAndDelete('show'));
          } else {
            $return = $this->redirect($refererHandler->getRefererAndDelete('edit'));
          }

          return $return;
        }

      } else {
        //bindAndSave not ok
      }

    }
    else
    {
      $this->ullwiki = $this->getWikiFromRequestOrCreate();	

      $this->forward404Unless($this->ullwiki);

      $this->form = new ullWikiForm($this->ullwiki);
    }

  }


  public function executeDelete() {

    // check access
    $this->checkAccess('MasterAdmins');

    $ullwiki = $this->getWikiFromRequest();
    $ullwiki->delete();

    return $this->redirect('ullWiki/list');
  }


  protected function breadcrumbForIndex() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->addFinal(__('Wiki'));
  }

  protected function breadcrumbForList() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');
    $this->breadcrumbTree->addFinal(__('Result list', null, 'common'));
  }  

  protected function breadcrumbForShow() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');

    // display result list link only when there is a "show" or "edit" referer containing 
    //  the list action    
    if (
      strstr($this->refererHandler->getReferer('show'), 'ullWiki/list')
      or strstr($this->refererHandler->getReferer('edit'), 'ullWiki/list')
      ) {
      $this->breadcrumbTree->add(
        __('Result list', null, 'common'),
        $this->refererHandler->getReferer()
      );
    }

    $this->breadcrumbTree->add(__('Show', null, 'common'));    
    $this->breadcrumbTree->addFinal($this->ullwiki->getSubject());

  }  


  protected function breadcrumbForEdit() {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');

    // display result list link only when there is a "show" or "edit" referer 
    //  containing the list action    
    if (
      strstr($this->refererHandler->getReferer('show'), 'ullWiki/list')
      or strstr($this->refererHandler->getReferer('edit'), 'ullWiki/list')
       ) {
      $this->breadcrumbTree->add(
        __('Result list', null, 'common'),
        $this->refererHandler->getReferer()
      );
    }

    // display breadcrumb show link only when there is an "edit" referer 
    //  containing the show action 
    if (strstr($this->refererHandler->getReferer('edit'), 'ullWiki/show')) {
      $this->breadcrumbTree->add(
        __('Result list', null, 'common'),
        $this->refererHandler->getReferer()
      );
    }



//    if ($this->refererHandler->hasReferer('show')) {
//      $this->breadcrumbTree->add(
//        __('Result list'),
//        $this->refererHandler->getReferer('show')
//      );      
//      $this->breadcrumbTree->add(
//        __('Show'),
//        $this->refererHandler->getReferer()
//      );
//      
//    } else {
//      $this->breadcrumbTree->add(
//        $this->getContext()->getI18N()->__('Result list'),
//        $this->refererHandler->getReferer()
//      );       
//    }
    $this->breadcrumbTree->add(__('Edit', null, 'common'));
  }


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


  protected function getWikiFromRequest()
  {
    $this->forward404Unless($this->getRequestParameter('docid') > 0, 'DOCID is mandatory');

    return $this->getWikiFromRequestOrCreate();
  }

  protected function getWikiFromRequestOrCreate()
  {
    if (($id = $this->getRequestParameter('docid')) > 0) 
    {
      $wiki = UllWikiTable::findByDocid($this->getRequestParameter('docid'));

      $this->forward404Unless($wiki);

      return $wiki;
    }
    else
    {
      return new UllWiki();
    }
  }

}
