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

    $this->mailing_lists = UllNewsletterMailingListTable::findActive();
    
    $this->breadcrumbForIndex();
  }
  
  /**
   * Execute show action
   */
  public function executeShow(sfRequest $request)
  {
    $this->checkPermission('ull_newsletter_show');
//    $this->breadcrumbForShow();
    
    // Normal show action
    if ($id = $request->getParameter('id'))
    {
      $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById($id);
      if (!$edition)
      {
        $this->redirect404();
      }
      
      if (UllUserTable::hasPermission('ull_newsletter_edit'))
      {
        $edition->body = '<span class="ull_cms_content_heading_edit_link">' .
          link_to(ull_image_tag('edit'), 'ullNewsletter' . '/edit?id=' . $edition->id) .
          '</span>' . 
          $edition->body;
      }
      
      
      $originalCulture = $this->getUser()->getCulture();
      $this->getUser()->setCulture($edition->sender_culture);

      $guest = new UllUser();
      $guest->last_name = __('Guest', null, 'ullMailMessages');
      $guest->id = 999999999;
      $guest->UllNewsletterMailingLists = $edition->getUllNewsletterMailingLists();
      
      $this->getUser()->setCulture($originalCulture);
      
      $body = $edition->getDecoratedBody();
      $body = $edition->getPersonalizedBody($body, $guest);
      $body = Swift_Plugins_ullPersonalizePlugin::personalizeBody($body, $guest);
      
      $this->allow_edit = false;
      
      if ($request->hasParameter('preview'))
      {
        $body = Swift_Plugins_ullPersonalizePlugin::removeTrackingBeaconTag($body);
      }
      else
      {
        $body = Swift_Plugins_ullPersonalizePlugin::removePersonalisationTags($body);
      }
      
      // Make relative links for images etc.
      $absoluteUrlPart = 'http://' . sfConfig::get('app_server_name');
      $body = str_replace($absoluteUrlPart, '', $body);
      
      $this->setVar('edition', $edition, true);
      $this->setVar('body', $body, true);
      
      $this->getResponse()->setTitle(__('Newsletter', null, 'ullMailMessages') . ' "' . $edition->subject . '"');
      
      return $this->renderText($body);
    }
    
    // Via online view link in email
    else 
    {
      $ullCrypt = ullCrypt::getInstance();
      
      $loggedMessage = Doctrine::getTable('UllMailLoggedMessage')
        ->findOneById($ullCrypt->decryptBase64($request->getParameter('mid'), true));
      $this->forward404If($loggedMessage === false); 
      
      //make use of the information that the user clicked on the read-online link     
      $loggedMessage->handleTrackingRequest($request);
      
      //remove online link and tracking beacon (since we are in online
      //view mode anyway and tracking was already handled) 
      $body = $loggedMessage['html_body'];
      
      $body = Swift_Plugins_ullPersonalizePlugin::removeOnlineLinkTag($body);
      $body = Swift_Plugins_ullPersonalizePlugin::removeTrackingBeaconTag($body);
      
      $this->getResponse()->setTitle(__('Newsletter', null, 'ullMailMessages') . ' "' . $loggedMessage->subject . '"');
      
      return $this->renderText($body);
    }
    
    
  }
  
  /**
   * Execute list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_list');
    
    parent::executeList($request);
  }
  
  /**
   * Execute edit action
   * 
   * @param sfWebRequest $request
   */  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_edit');
    
//     if ($request->isMethod('post')) {
//       var_dump($request->getParameterHolder()->getAll());die;
//     }
    
    parent::executeEdit($request);
    
    $this->already_sent = (boolean) $this->generator->getRow()->submitted_at;
  }
  
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions::executePostSave()
   */
  protected function executePostSave(Doctrine_Record $row, sfRequest $request)
  { 
    $user = UllUserTable::findLoggedInUser();
    
    $mail = $row->createMailMessage($user);
    
    if ($request->getParameter('action_slug') == 'send_test')
    {
      $mail->addAddress($user);
      $mail->setSubject($mail->getSubject() . '   #Test#');
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

    if ($request->getParameter('action_slug') == 'save_show')
    {
      $this->redirect('ullNewsletter/show?preview=true&id=' . $row->id);
    }
    
    if (
      $request->getParameter('action_slug') == 'send_test' ||
      $request->getParameter('action_slug') == 'save_only'
    ) 
    {
      $this->redirect(ullCoreTools::appendParamsToUri(
        $this->edit_base_uri, 'id=' . $row->id
      ));
    }
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
    
    
  }

  /**
   * Execute "public" action
   * 
   * Typical page to be publicly included on website
   * Features subscription and archive
   * 
   * @param sfRequest $request
   */
  public function executePublic(sfRequest $request)
  {
    $this->checkPermission('ull_newsletter_public');
    
    $userGenerator = new ullUserGenerator('w');
    
    $this->adjustPublicUserColumnsConfig($userGenerator);
    
    $user = new UllUser;
    $userGenerator->buildForm($user);
    
    if ($request->isMethod('post'))
    {
      $fields = $request->getParameter('fields');
      $fields = $this->handlePublicSubscriptionDefaultMailingLists($userGenerator, $fields);
      
      $form = $userGenerator->getForm();
      $form->bind($fields);
      
      if ($form->isValid())
      {
        $this->handleSubscription($userGenerator);
        
        $this->getUser()->setFlash('message', __('Thank you for your subscription!', null, 'ullMailMessages'));
        $this->redirect('ullNewsletter/public');
      }
      else 
      {
        $errorSchema = $userGenerator->getForm()->getErrorSchema();
        $emailError = $errorSchema['email'];
        
        if ($emailError && get_class($emailError->getValidator()) == 'sfValidatorDoctrineUnique')
        {
          $this->getUser()->setFlash('message', __('You\'re already subscribed.', null, 'ullMailMessages'));
          $this->redirect('ullNewsletter/public');
        }
      }
    }    
    
    $this->setVar('user_generator', $userGenerator, true);
    
    $this->setVar('mailing_lists', UllNewsletterMailingListTable::findPublic());
    
  }
  
  /**
   * Actual subscription for the public action.
   * 
   * Can be overwritten to customize
   
   * @param ullGenerator $generator
   */
  protected function handleSubscription(ullGenerator $generator)
  {
    $generator->getForm()->save();
  }
  
  /**
   * Adjust newsletter subscription columnsConfig for public action
   *
   * @param ullUserGenerator $generator
   */
  protected function adjustPublicUserColumnsConfig(ullUserGenerator $generator)
  {
    $columnsConfig = $generator->getColumnsConfig();
    $columns = sfConfig::get('app_ull_newsletter_public_subscription_columns',
      array(
        'id',
        'first_name',
        'last_name',
        'email',
      )
    );
    
    $columnsConfig->disableAllExcept(array_merge($columns, array('UllNewsletterMailingLists')));
    
    // Don't disable UllNewsletterMailingList but do not render it
    if (!in_array('UllNewsletterMailingLists', $columns))
    {
      $columnsConfig['UllNewsletterMailingLists']->setAutoRender(false);
    }
    
    $columnsConfig->order($columns);
    
    $required = sfConfig::get('app_ull_newsletter_public_subscription_required_columns',
      array(
        'email',
      )
    );
    
    foreach($columns as $column)
    {
      $columnsConfig[$column]->setIsRequired(false);
    }
    
    foreach($required as $column)
    {
      $columnsConfig[$column]->setIsRequired(true);
    }
    
    $columnsConfig['email']->setUnique(true);

    
  }

  /**
   * Subscribe to the default lists if the mailing list selection is disabled
   * 
   * @param ullUserGenerator $generator
   * @param array $fields
   */
  protected function handlePublicSubscriptionDefaultMailingLists(ullUserGenerator $generator, $fields)
  {
    $active = array_keys($generator->getAutoRenderedColumns());
    
    if (!in_array('UllNewsletterMailingLists', $active))
    {
      $ids = UllNewsletterMailingListTable::getSubscribedByDefaultIds();
      $fields['UllNewsletterMailingLists'] = $ids;
    }
    
    return $fields;
  }
  
  
  
  /**
   * Execute unsubscribe action
   * 
   * @param sfRequest $request
   */
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

        $this->notifyUnsubscribe($user, $list);
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
  
  /**
   * Send notify when someone unssubscribes from a mailing list
   * @param UllUser $user
   * @param UllNewsletterMailingList $list
   */
  protected function notifyUnsubscribe(UllUser $user, UllNewsletterMailingList $list)
  {
    if (!sfConfig::get('app_ull_newsletter_unsubscribe_notify_email'))
    {
      return;
    }
    
    $mail = new ullsfMail('ull_newsletter_unsubscribe_notify');
    $mail->setFrom(sfConfig::get('app_ull_newsletter_from_address'));
    $mail->addAddress(sfConfig::get('app_ull_newsletter_unsubscribe_notify_email'));
    $mail->setSubject(__(
      '%email% unsubscribed from list %list%',
      array('%email%' => $user->email, '%list%' => (string) $list),
      'ullMailMessages'
    ));
    $mail->setBody(__(
      'The following user unsubscribed:
    
Name:  %name%
Email: %email%
List:  %list%

User details: %url%',
      array(
        '%name%' => (string) $user,
        '%email%' => $user->email, 
        '%list%' => (string) $list,
        '%url%' => url_for('ullUser/edit?id=' . $user->id, true)
      ),
      'ullMailMessages'
    ));
    
    $mail->send();
  }

  /**
   * Render transparent 1x1 image to track email readings
   * 
   * @param sfRequest $request
   */
  public function executeTrackWebBeacon(sfRequest $request)
  {
    $ullCrypt = ullCrypt::getInstance();
    
    $loggedMessage = Doctrine::getTable('UllMailLoggedMessage')
      ->findOneById($ullCrypt->decryptBase64($request->getParameter('mid'), true));

    if ($loggedMessage !== false  && 
      $loggedMessage->UllNewsletterEdition->exists())
    {
      //make use of the information that the user opened the mail
      //and that the mail client requested the tracking image
      $loggedMessage->handleTrackingRequest($request);
      
      $this->getLogger()->crit('ullNewsletter: handled beacon ok: ' . $loggedMessage->id);

    }
    else
    {
      $this->getLogger()->crit('ullNewsletter: beacon failed');
    }
    
    $imagePath = sfConfig::get('sf_root_dir') . '/plugins/ullMailTheme' .
      sfConfig::get('app_theme_package', 'NG') . 'Plugin/web/images/beacon.png';
    $beaconImage = file_get_contents($imagePath);          
    
    //serve the 1x1 transparent gif
    $this->getResponse()->setContentType('image/png');
    
    return $this->renderText($beaconImage);
  }
  
  /**
   * Import of recipients from a csv file
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions::executeCsvUpload()
   */
  public function executeCsvImport(sfRequest $request)
  {
    $this->checkPermission('ull_newsletter_csv_import');
    
    if (!$this->mapper_class)
    {
      $this->mapper_class = 'ullDoctrineMapperNewsletter';
    }

    if (!$this->custom_message)
    {
      $this->custom_message = __(
        'Upload a csv file with your data and the following column headers: %columns%', 
        array('%columns%' => '| First name | Last name | Email | Mailing list |'),
        'ullMailMessages'
      );
    }
    
    $this->breadcrumbForCsvImport();
        
    parent::executeCsvImport($request);
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
        $this->q->addWhere('UllNewsletterMailingLists->id = ?', $mailingListId);
        
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
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getEditGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullNewsletterGenerator('w');
  }

  /**
   * Define generator for delete action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getDeleteGenerator()
   */
  protected function getDeleteGenerator()
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
  
  /**
   * Handles breadcrumb for show action
   */
  protected function breadcrumbForCsvImport()
  {
    $breadcrumb_tree = new ullNewsletterBreadcrumbTree();
    $breadcrumb_tree->add(__('Recipient import', null, 'ullMailMessages'));
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }    
    
}