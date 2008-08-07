<?php

/**
 * user actions.
 *
 * @package    ull_at
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseullUserActions extends ullsfActions
{
  
  /*
   * Basic actions for useradmin
   */
  /*
  public function executeIndex() {
    
  // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('ullUser', 'ullUser');    
    
    
    // check access
    $this->checkAccess(1);
    
  }

  public function executeList() {
    
    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('ullUser', 'ullUser');
    $this->breadcrumbTree->addFinal(__('list'));
           
    // check access
    $this->checkAccess(1);

    $this->ull_users = UllUserPeer::doSelect(new Criteria());
  }

  public function executeShow()
  {
    // check access
    $this->checkAccess(1);
        
    $this->ull_user = UllUserPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->ull_user);
  }

  public function executeCreate()
  {
    // check access
    $this->checkAccess(1);
        
    $this->ull_user = new UllUser();

    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
    // check access
    $this->checkAccess(1);

    $this->ull_user = UllUserPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->ull_user);
  }

  public function executeUpdate()
  {
    // check access
    $this->checkAccess(1);
        
    
    if (!$this->getRequestParameter('id'))
    {
      $ull_user = new UllUser();
    }
    else
    {
      $ull_user = UllUserPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($ull_user);
    }

    $ull_user->setId($this->getRequestParameter('id'));
    $ull_user->setFirstName($this->getRequestParameter('first_name'));
    $ull_user->setLastName($this->getRequestParameter('last_name'));
    $ull_user->setEmail($this->getRequestParameter('email'));
    $ull_user->setUsername($this->getRequestParameter('username'));
    $ull_user->setPassword($this->getRequestParameter('password'));
    $ull_user->setLocationId($this->getRequestParameter('location_id'));
    $ull_user->setUserType($this->getRequestParameter('user_type'));
    $ull_user->setCreatorUserId($this->getRequestParameter('creator_user_id'));
    $ull_user->setUpdatorUserId($this->getRequestParameter('updator_user_id'));

    $ull_user->save();

    return $this->redirect('ullUser/show?id='.$ull_user->getId());
  }

  public function executeDelete()
  { 
    // check access
    $this->checkAccess(1);
    
    $ull_user = UllUserPeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($ull_user);

    $ull_user->delete();

    return $this->redirect('ullUser/list');
  }  
  */
  
  
  /*
   * Other actions (Login,...)
   */
  
  public function executeLogin() {
    
    //check context
    if ($this->getRequest()->getMethod() != sfRequest::POST) {
      
        // login form
        $refererHandler = new refererHandler();
        $refererHandler->initialize();
        
        if ($this->getRequestParameter('option') == 'noaccess') {
          $this->msg = 'Please login to verify access';
        }
        
        return sfView::SUCCESS;
        
    } else { 
      

        
      // handle the form submission
      $username = $this->getRequestParameter('username');
      $password = $this->getRequestParameter('password');
   
      $c = new Criteria();
      $c->add(UllUserPeer::USERNAME, $username);
      $user = UllUserPeer::doSelectOne($c);
      
      // username exists?
      if ($user) {
        
//        echo md5($password) . ' - ' . $user->getPassword();
        
        // password is OK?
        
        $auth_class = 'ullAuth' 
          . sfInflector::classify(sfConfig::get('app_auth_function', 'default'));
        
//        ullCoreTools::printR($auth_class);
//        exit();
          
        if (call_user_func($auth_class . '::authenticate', $user, $password)) {
          
            
          
  //        $this->getUser()->setAuthenticated(true);
  //        $this->getUser()->addCredential('subscriber');
   
  //        $this->getUser()->setAttribute('subscriber_id', $user->getId(), 'subscriber');
  //        $this->getUser()->setAttribute('nickname', $user->getNickname(), 'subscriber');
  
          $this->getUser()->setAttribute('user_id', $user->getId());
          
//          ullCoreTools::printR($this->getUser()->getAttribute('user_id'));
//          echo "hallo";
//          exit();
   
          // redirect to last page
          $refererHandler = new refererHandler();
          return $this->redirect($refererHandler->getRefererAndDelete('login'));
  //        return $this->redirect($this->getRequestParameter('referer', '@homepage'));
        }
      }
    $this->msg = $this->getContext()->getI18N()->__('Login failed. Please try again:'); 
    return sfView::SUCCESS;
    }
  }
  
  public function executeLogout() {
    $this->getUser()->setAttribute('user_id',0);
    
//    $this->getUser()->clearCredentials();
//    $this->getUser()->getAttributeHolder()->removeNamespace('subscriber');
   
    $this->redirect('@homepage');
  }
  
  public function handleErrorLogin() {
//    $this->forward('user', 'login');
    return sfView::SUCCESS;
  }
  
  public function executeNoaccess() {
    
    $this->refererHandler = new refererHandler();
    
    if (!$this->getUser()->getAttribute('user_id')) {
      $this->refererHandler->initialize('login');
      return $this->redirect('ullUser/login?option=noaccess');
    }    

    $this->refererHandler->initialize('access');
//    $this->login = $this->getRequest('login');
    return sfView::SUCCESS;
  }
  
  
  
}
