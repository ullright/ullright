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

  public function executeIndex() 
  {
    // referer handling -> reset all wiki referers 
    $refererHandler = new refererHandler();
    $refererHandler->delete('show');
    $refererHandler->delete('edit');

    // == handle request params

    // allow ullwiki to be used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');

    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->addFinal(__('Wiki'));

    $this->ullwiki_pager = new sfDoctrinePager('UllWiki', 25);
    $this->ullwiki_pager->setPage($this->getRequestParameter('page', 1));
    $this->ullwiki_pager->getQuery();
    $this->ullwiki_pager->init();
  }



  public function executeList() {

    // referer handling -> reset all wiki referers
    $refererHandler = new refererHandler();
    $refererHandler->delete('show');
    $refererHandler->delete('edit');


    // == handle request params

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
//    $this->return_url = $this->getRequestParameter('return_url');
    $this->return_var = $this->getRequestParameter('return_var');


    $this->ull_reqpass_redirect();

    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');
    $this->breadcrumbTree->addFinal(__('Result list', null, 'common'));




    // build query
    $q = new Doctrine_Query();
    $q->from('UllWiki w')
      ->where('w.current = ?', true)
    ;


    if ($this->getRequestParameter('sort')) {
    	$q->orderBy('w.'.$this->getRequestParameter('sort').' ASC');
    } else {
      $q->orderBy('w.updated_at DESC');
    }

    if ($this->search = $this->getRequestParameter('search')) {

//      ullCoreTools::printR($this->search);

      $fulltext = $this->getRequestParameter('fulltext');

      $query_subject = '';
      $query_tags = '';
      $query_body = '';

      $search_words_arr = explode(' ', $this->search);
      foreach($search_words_arr as $key => $search_word) {
        #$search_words_arr[$key] = '%'.$search_word.'%';
        $search_word = '"%'.$search_word.'%"';

        $query_subject .= ($query_subject!=''?' AND ':'') . 'w.subject LIKE '.$search_word;
        $query_tags    .= ($query_tags!=''?' AND ':'')    . 'w.duplicate_tags_for_propel_search LIKE '.$search_word;

        if ($fulltext) {
          $query_body  .= ($query_body!=''?' AND ':'')    . 'w.body LIKE '.$search_word;
        }
      }

      $q->addWhere($query_subject . ' OR ' . $query_tags . ($query_body!=''?' OR ':'') . $query_body);
    }

    $this->ullwiki_pager = new sfDoctrinePager('ullWiki', 25);
    $this->ullwiki_pager->setPage($this->getRequestParameter('page', 1));
    $this->ullwiki_pager->setQuery($q);
    $this->ullwiki_pager->init();
  }



  public function executeShow() {

    // referer handling -> reset show referer
    $refererHandler = new refererHandler();
    $refererHandler->initialize('show');

    // == handle request params


    // get document
    $this->ullwiki = UllWikiTable::findByDocid($this->getRequestParameter('docid'));
    $this->forward404Unless($this->ullwiki);

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    if ($this->return_var = $this->getRequestParameter('return_var')) {
       $this->return_url = $this->getUser()->getAttribute('wiki_return_url') 
        . '&' . $this->return_var . '=' . $this->ullwiki->getDocId(); ;
    }


    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Wiki'), 'ullWiki/index');




    // display result list link only when there is a "show" or "edit" referer containing 
    //  the list action    
    if (
      strstr($refererHandler->getReferer('show'), 'ullWiki/list')
      or strstr($refererHandler->getReferer('edit'), 'ullWiki/list')
      ) {
    $this->breadcrumbTree->add(
        __('Result list', null, 'common'),
        $refererHandler->getReferer()
      );
    }

    $this->breadcrumbTree->add(__('Show', null, 'common'));    
    $this->breadcrumbTree->addFinal($this->ullwiki->getSubject());

    // variable definition
//    $this->previous_cursor = 0;
//    $this->next_cursor = 0;

//    if ($this->getRequestParameter('cursor')) {
//            
////      $c = $this->getFlash('c');   
////      WikiPeer::doSelectWithI18n($c, '');
////      $this->setFlash('c', $c);
//      
//      $pager = new sfPropelPager('Wiki');
////      $pager->setCriteria($c);      
//      $pager->setPage($this->getRequestParameter('page', 1));
//      $pager->init();
////      $pager->setCursor($this->getRequestParameter('cursor'));
////      
////      $this->previous_wiki  = $pager->getPrevious();
////      if ($this->previous_wiki) {
////        $this->previous_cursor = $this->getRequestParameter('cursor') - 1;
////      }
////      $this->wiki           = $pager->getCurrent();
////      $this->next_wiki      = $pager->getNext();
////      if ($this->next_wiki) {
////        $this->next_cursor = $this->getRequestParameter('cursor') + 1;
////      }
//          
//    } elseif ($this->getRequestParameter('id')) {
      
//      $this->wiki = WikiPeer::retrieveByPK($this->getRequestParameter('id'));
//    }


    $this->forward404Unless($this->ullwiki);
  }

  public function executeCreate() {

    // "wiki create" referer handling
    // this has to take place before the access check, to correctly set the 
    //   referer, eg. for the 'cancel' button 
    $this->refererHandler = new refererHandler();  
    $this->refererHandler->initialize('edit');

    // check access
    $this->checkAccess('MasterAdmins');


    // == handle request params

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
//    $this->return_url = $this->getRequestParameter('return_url');
    $this->return_var = $this->getRequestParameter('return_var');    

    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
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

//    $this->breadcrumbTree->add(
//      __('Result list', null, 'common'),
//      $this->refererHandler->getReferer('edit')
//    );
    $this->breadcrumbTree->addFinal(__('Create', null, 'common'));

    $this->ullwiki = new UllWiki();
    $this->setTemplate('edit');

    /*
    $c = new Criteria;
    $c->addAscendingOrderByColumn(UllCulturePeer::NAME);
    $this->cultures = UllCulturePeer::doSelect($c);
    */
  }


  public function executeEdit() {

    $this->refererHandler = new refererHandler();
    $this->refererHandler->initialize();

    // check access
    $this->checkAccess('MasterAdmins');

    // == handle request params

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
//    $this->return_url = $this->getRequestParameter('return_url');
    $this->return_var = $this->getRequestParameter('return_var');    

    // breadcrumb
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

//    $this->wiki = WikiPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->ullwiki = UllWikiTable::findByDocid($this->getRequestParameter('docid'));
//    $this->wiki->setCulture('');

/*
    // get list of cultures for language select
    $c = new Criteria;
    $c->addAscendingOrderByColumn(UllCulturePeer::NAME);
    $this->cultures = UllCulturePeer::doSelect($c);
*/

    $this->forward404Unless($this->ullwiki);
  }



  public function executeUpdate() {

    // check access
    $this->checkAccess('MasterAdmins');

    $logged_in_user_id = $this->getUser()->getAttribute('user_id');

    $ullwiki = new UllWiki();

    // check if this is a new or existing wiki article
    if ($docid = $this->getRequestParameter('docid')) {
      $ullwiki->setDocid($docid); // keep docid
      UllWikiTable::setOldDocsNonCurrent($docid);
      #$ullwiki->setOldDocsNonCurrent(); // remove 'current' flag from old entries
      $ullwiki->setCreatorUserId($this->getRequestParameter('creator_user_id')); // keep creator
      $ullwiki->setCreatedAt($this->getRequestParameter('created_at')); // keep createdate
    } else {
      $docid = UllWikiTable::getNextFreeDocid();
      $ullwiki->setDocid($docid);
      $ullwiki->setCreatorUserId($logged_in_user_id);
    }

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    if ($return_var = $this->getRequestParameter('return_var')) {
       $return_url = $this->getUser()->getAttribute('wiki_return_url') 
        . '&' . $return_var . '=' . $docid;
    }

    $ullwiki->setCurrent(true);
//    $edit_counter = $this->getRequestParameter('edit_counter');
//    $edit_counter++;
//    $wiki->setEditCounter($edit_counter);
    $ullwiki->setEditCounter($this->getRequestParameter('edit_counter') + 1);
    $ullwiki->setLockedByUserId($this->getRequestParameter('locked_by_user_id') ? $this->getRequestParameter('locked_by_user_id') : null);
    if ($this->getRequestParameter('locked_at')) {
      list($d, $m, $y) = sfI18N::getDateForCulture($this->getRequestParameter('locked_at'), $this->getUser()->getCulture());
      $ullwiki->setLockedAt("$y-$m-$d");
    }
//    $wiki->setCreatorUserId($this->getRequestParameter('creator_user_id') ? $this->getRequestParameter('creator_user_id') : null);
    $ullwiki->setUpdatorUserId($logged_in_user_id);

//    die ("culture: ".$this->getRequestParameter('culture_id'));
    //$culture = UllCulturePeer::retrieveByPK($this->getRequestParameter('ull_culture_id'));
//    echo "###".$this->getRequestParameter('culture_id')."###".$culture->getIsoCode()."###";
//    exit();

/* TODO
    if (!is_object($culture)) {
      ullCoreTools::printR($culture);
      ullCoreTools::printR($this);
      exit();
    }
*/

    #$ullwiki->setCulture($culture->getIsoCode());
    $ullwiki->setCulture('en');
    $ullwiki->setSubject($this->getRequestParameter('subject'));
    $ullwiki->setBody($this->getRequestParameter('body'));
    $ullwiki->setChangelogComment($this->getRequestParameter('changelog_comment'));
    //$ullwiki->setTags(strtolower($this->getRequestParameter('tags'))); TODO
    $ullwiki->setDuplicateTagsForPropelSearch(strtolower($this->getRequestParameter('tags')));

    $ullwiki->save();



    // == forward junction
    if ($this->getRequestParameter('save_mode') == 'saveonly') {
      return $this->redirect('ullWiki/edit?docid='.$docid);

      // plugin mode
    } elseif (isset($return_url)) {
//      $url = $return_url . '&' . $return_var . '=' . $docid;
//      ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
//      ullCoreTools::printR($url);
//      exit();
      return $this->redirect($return_url);

    } elseif ($this->getRequestParameter('save_mode') == 'saveshow') {
      return $this->redirect('ullWiki/show?docid='.$docid);
      
    } else {
      
      // save and exit:
//      ullCoreTools::printR($this->getUser()->getAttributeHolder());
//      exit();
      
      $refererHandler = new refererHandler();
      
      // skip returning to the show action -> jump directly to the pervious result list
      if ($refererHandler->hasReferer('show')) {
        $refererHandler->delete('edit');
        $return = $this->redirect($refererHandler->getRefererAndDelete('show'));
      } else {
        $return = $this->redirect($refererHandler->getRefererAndDelete('edit'));
      }
      
//      ullCoreTools::printR($return);
      
      return $return;
      //exit();
      
//      if ($this->getRequestParameter('docid')) { // new?
//        return $this->redirect($this->getUser()->getAttribute('referer_wiki_edit'));
//      } else {
//        return $this->redirect($this->getUser()->getAttribute('referer_wiki_create'));
//      }
        
    }
    
//    return $this->redirect('wiki/show?id='.$wiki->getId());
  }

  public function executeDelete() {

    // check access
    $this->checkAccess('MasterAdmins');

    $ullwiki = UllWikiTable::findByDocid($this->getRequestParameter('docid'));

    $this->forward404Unless($ullwiki);

    //$wiki->delete();
    $ullwiki->setCurrent(false);
    $ullwiki->save();

    return $this->redirect('ullWiki/list');
  }

}
