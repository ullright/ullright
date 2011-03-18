<?php

/**
 * ullNewsletter actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllNewsletterActions extends BaseUllGeneratorActions
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
    $this->checkPermission('ull_newsletter_index');
    
    $this->form = new ullFilterForm;

    $this->named_queries = new ullNamedQueriesUllNewsletter;

    $q = new Doctrine_Query();
    $q
      ->from('UllNewsletterMailingList')
      ->orderBy('name')
    ;
    $this->mailing_lists = $q->execute();
    
    $this->breadcrumbForIndex();
  }
  
  public function executeShow(sfRequest $request)
  {
    $this->checkPermission('ull_newsletter_show');
    $this->breadcrumbForShow();
    
    $ullCrypt = ullCrypt::getInstance();
    
    $loggedMessage = Doctrine::getTable('UllMailLoggedMessage')
      ->findOneById($ullCrypt->decryptBase64($request->getParameter('mid'), true));
    $this->forward404If($loggedMessage === false); 
    
    //make use of the information that the user clicked on the read-online link     
    $loggedMessage->handleTrackingRequest($request);
    $loggedMessage->save();
    
    //remove online link and tracking beacon (since we are in online
    //view mode anyway and tracking was already handled) 
    $onlineBody = $loggedMessage['html_body'];
    
    $onlineBody = preg_replace(
    '/<span.*?id\s*=\s*"ull_newsletter_show_online_link".*?>.*?<\/span>/',
    	'', $onlineBody);
    
    $onlineBody = preg_replace(
    '/<img.*?id\s*=\s*"ull_newsletter_beacon".*?\/>/',
    	'', $onlineBody);
    
    return $this->renderText($onlineBody);
  }
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_list');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_edit');
    
    parent::executeEdit($request);
    
    $this->already_sent = (boolean) $this->generator->getRow()->submitted_at;
  }
  
  
  protected function executePostSave(Doctrine_Record $row, sfRequest $request)
  { 
    $user = UllUserTable::findLoggedInUser();
    
    $mail = $row->createMailMessage($user);
    
    if ($request->getParameter('action_slug') == 'send_test')
    {
      $mail->addAddress($user);
      $this->getMailer()->send($mail);  
      
      $this->getUser()->setFlash('message', 
        __('Test newsletter was sent to %email%', 
          array('%email%' => $user['email']), 'ullMailMessages') . '.'
      );      
    }
    
    if ($request->getParameter('action_slug') == 'send')
    {
      if ($row->submitted_at)
      {
        $this->getUser()->setFlash('message', 
          __('This newsletter has already been sent', null, 'ullMailMessages') . '!'
        );
        
        $this->redirect(ullCoreTools::appendParamsToUri(
          $this->edit_base_uri, 
          'id=' . $this->generator->getForm()->getObject()->id
        ));
      }
      
//      $recipients = $row->findRecipients(Doctrine::HYDRATE_ARRAY);
      $numOfRecipients = $row->countRecipients();
      
      if (!$numOfRecipients)
      {
        $this->getUser()->setFlash('message', 
          __('No recipients found. Please select one or more mailing lists with subscribers', null, 'ullMailMessages') . '.'
        );
        
        $this->redirect(ullCoreTools::appendParamsToUri(
          $this->edit_base_uri, 
          'id=' . $this->generator->getForm()->getObject()->id
        ));
      }
      
      $row['submitted_at'] = date('Y-m-d H:i:s');
      $row['submitted_by_ull_user_id'] = $user->id;
      $row['sender_culture'] = $this->getUser()->getCulture();
      $row['num_total_recipients'] = $numOfRecipients;
      $row->save();
      
      $this->getUser()->setFlash('message', 
        __('The newsletter is being sent to %number% recipients', 
          array('%number%' => $row['num_total_recipients']), 'ullMailMessages') . '.'
      );
    }      
    
    if (
      $request->getParameter('action_slug') == 'send_test' ||
      $request->getParameter('action_slug') == 'save_only'
    ) 
    {
      $this->redirect(ullCoreTools::appendParamsToUri(
        $this->edit_base_uri, 
        'id=' . $this->generator->getForm()->getObject()->id
      ));
    }
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
    
    
  }  
  
  public function executeUnsubscribe(sfRequest $request)
  {
    $list = Doctrine::getTable('UllNewsletterMailingList')->findOneBySlug(
      $request->getParameter('list'));
      
    if (!$list)
    {
      $this->getUser()->setFlash('message', 
        __('Mailing list not found', null, 'ullMailMessages') . '.'
      , false);

      return;
    }

    $user = Doctrine::getTable('UllUser')->find($request->getParameter('s_uid'));
      
    if (!$user)
    {
      $this->getUser()->setFlash('message', 
        __('User not found', null, 'ullMailMessages') . '.'
      , false);

      return;     
    }
    
    if ($request->getParameter('confirm') !== '1')
    {
      $this->getUser()->setFlash('message', 
        __('Are you sure you want to unsubscribe from list "%list%"?', 
          array('%list%' => $list['name']), 'ullMailMessages') . '<br /><br />' .
          ull_link_to(__('Confirm', null, 'common'), array('confirm' => 1))
      , false);
    }
    else
    {
      //do the actual unsubscribing
      $num = $list->unsubscribeUsers($user);
      
      if ($num)
      {
        $this->getUser()->setFlash('message', 
          __('You have been successfully unsubscribed from list "%list%"', 
            array('%list%' => $list['name']), 'ullMailMessages') . '.'
        , false);
      }
      else
      {
        $this->getUser()->setFlash('message', 
          __('You are not subscribed to "%list%"', 
            array('%list%' => $list['name']), 'ullMailMessages') . '.'
        , false);      
      }
    }
  }

  public function executeTrackWebBeacon(sfRequest $request)
  {
    $ullCrypt = ullCrypt::getInstance();
    
    $loggedMessage = Doctrine::getTable('UllMailLoggedMessage')
      ->findOneById($ullCrypt->decryptBase64($request->getParameter('mid'), true));
    
    if ($loggedMessage !== false)
    {
      //make use of the information that the user opened the mail
      //and that the mail client requested the tracking image
      $loggedMessage->handleTrackingRequest($request);
      $loggedMessage->save();
    }
    
    //serve the 1x1 transparent gif
    $this->getResponse()->setContentType('image/gif');
    
    $imagePath = sfConfig::get('sf_root_dir') . '/plugins/ullCoreTheme' .
      sfConfig::get('app_theme_package', 'NG') . 'Plugin/web/images/beacon.gif';
    $beaconImage = file_get_contents($imagePath);
    print ($beaconImage);
    
    return sfView::NONE;
  }
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    //filter per mailing list
    if ($mailingListId = $this->filter_form->getValue('ull_newsletter_mailing_list_id'))
    {
      $mailingList = Doctrine::getTable('UllNewsletterMailingList')->findOneById($mailingListId);
      
      if ($mailingList !== false)
      {
        $this->q->addWhere('UllNewsletterEditionMailingLists->id = ?', $mailingListId);
        
        $this->ull_filter->add('filter[ull_newsletter_mailing_list_id]',
          __('Mailing list', null, 'ullMailMessages') . ': ' . $mailingList);
      }
    }
  } 
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullNewsletterGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullNewsletterGenerator('w');
  } 
  
  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $breadcrumbTree = new ullNewsletterBreadcrumbTree();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new ullNewsletterBreadcrumbTree();
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullNewsletter/list');
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for show action
   */
  protected function breadcrumbForShow()
  {
    $breadcrumb_tree = new ullNewsletterBreadcrumbTree();
    $breadcrumb_tree->add(__('Show', null, 'common'));
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new ullNewsletterBreadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumb_tree->addDefaultListEntry();
    }    
    
    if ($this->id) 
    {
      $breadcrumb_tree->add(__('Edit', null, 'common'));
    }
    else
    {
      $breadcrumb_tree->add(__('Create', null, 'common'));
    }
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
    
}