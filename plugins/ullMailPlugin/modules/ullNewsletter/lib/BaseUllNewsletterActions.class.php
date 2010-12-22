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
    
    $this->categories = array('Product News', 'Pest Practicies');
    
    //Add module stylsheet for all actions
    $path =  '/ullMailTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));    
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_index');
    
    $this->form = new ullFilterForm;
//    
    $this->named_queries = new ullNamedQueriesUllNewsletter;

    $this->breadcrumbForIndex();
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
    
    $this->already_sent = (boolean) $this->generator->getRow()->sent_at;
  }
  
  
  protected function executePostSave(Doctrine_Record $row, sfRequest $request)
  { 
    $mail = new ullsfMail();
    
    $user = UllUserTable::findLoggedInUser();
    $mail->setFrom($user->email, $user->display_name);
    
    $mail->setSubject($row['subject']);
    $mail->setHtmlBody($row->getDecoratedBody());
    
    $mail->setNewsletterEditionId($row['id']);
    
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
      if ($row->sent_at)
      {
        $this->getUser()->setFlash('message', 
          __('This newsletter has already been sent', null, 'ullMailMessages') . '!'
        );
        
        $this->redirect(ullCoreTools::appendParamsToUri(
          $this->edit_base_uri, 
          'id=' . $this->generator->getForm()->getObject()->id
        ));
      }
      
      if (!count($row->getRecipients()))
      {
        $this->getUser()->setFlash('message', 
          __('No recipients found. Please select one or more mailing lists with subscribers', null, 'ullMailMessages') . '.'
        );
        
        $this->redirect(ullCoreTools::appendParamsToUri(
          $this->edit_base_uri, 
          'id=' . $this->generator->getForm()->getObject()->id
        ));
      }
      
      
      //TODO: allow to give an array of UllUsers
      //TODO: add handling for multiple UllUsers for batchSend
      foreach ($row->getRecipients() as $recipient)
      {
        $mail->clearRecipients();
        $mail->addAddress($recipient);

        $this->getMailer()->sendQueue($mail);
      }  
      
      $row['sent_at'] = date('Y-m-d H:i:s');
      $row['sent_by_ull_user_id'] = $user->id;
      $row['num_sent_emails'] = count($row->getRecipients());
      $row->save();
      
      $this->getUser()->setFlash('message', 
        __('The newsletter has been sent to %number% recipients', 
          array('%number%' => $row['num_sent_emails']), 'ullMailMessages') . '.'
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
      );

      return;
    }

    $users = Doctrine::getTable('UllUser')->findByEmail(
      base64_decode($request->getParameter('email')));
      
    if (count($users) == 0)
    {
      $this->getUser()->setFlash('message', 
        __('User not found', null, 'ullMailMessages') . '.'
      );

      return;     
    }
    
    $num = $list->unsubscribeUsers($users);
    
    if ($num)
    {
      $this->getUser()->setFlash('message', 
        __('You have been successfully unsubscribed from list "%list%"', 
          array('%list%' => $list['name']), 'ullMailMessages') . '.'
      );
    }
    else
    {
      $this->getUser()->setFlash('message', 
        __('You are not subscribed to "%list%"', 
          array('%list%' => $list['name']), 'ullMailMessages') . '.'
      );      
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
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullNews/list');
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