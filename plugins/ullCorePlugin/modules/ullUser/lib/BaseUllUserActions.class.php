<?php

/**
 * user actions.
 *
 * @package    ull_at
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllUserActions extends ullsfActions
{
  
  /*
   * Other actions (Login,...)
   */
  
  public function executeLogin($request) 
  {
    $this->form = new LoginForm();
    
    //check context
    if ($request->isMethod('get')) 
    {
      // login form
      $refererHandler = new refererHandler();
      $refererHandler->initialize();
        
      if ($request->getParameter('option') == 'noaccess') 
      {
        $this->msg = 'Please login to verify access';
      }
      return sfView::SUCCESS;
    } 
    else 
    {
      $this->form->bind($request->getParameter('login'));
      
      if ($this->form->isValid())
      {
        // handle the form submission
        $username = $this->form->getValue('username');
        $password = $this->form->getValue('password');
        $js_check = $this->form->getValue('js_check');
  
        //user has javascript enabled?
        $this->getUser()->setAttribute('has_javascript', false);
        if ($js_check == 1) {
          $this->getUser()->setAttribute('has_javascript', true);
        }
  
        $user = Doctrine::getTable('UllUser')->findByName($username)->getFirst();
        
        if ($user !== false) 
        {
  //        echo md5($password) . ' - ' . $user->password;
          
          // authenticate        
          $auth_class = 'ullAuth' 
              . sfInflector::classify(sfConfig::get('app_auth_function', 'default'));
            
          if (call_user_func($auth_class . '::authenticate', $user, $password)) 
          {
            $this->getUser()->setAttribute('user_id', $user->getId());
            
            // redirect to last page
            $refererHandler = new refererHandler();
            return $this->redirect($refererHandler->getRefererAndDelete('login'));
          }
        }
      }
      
      $this->msg = $this->getContext()->getI18N()->__('Login failed. Please try again:'); 
//      return sfView::SUCCESS;
    }
  }
  
  public function executeLogout() 
  {
    $this->getUser()->setAttribute('user_id', 0);
    $this->getUser()->setAttribute('has_javascript', false);
    $this->redirect('@homepage');
  }
  
  public function handleErrorLogin() 
  {
    return sfView::SUCCESS;
  }
  
  public function executeNoaccess() 
  {
    $this->refererHandler = new refererHandler();
    
    if (!$this->getUser()->getAttribute('user_id')) 
    {
      $this->refererHandler->initialize('login');
      return $this->redirect('ullUser/login?option=noaccess');
    }    

    $this->refererHandler->initialize('access');
    return sfView::SUCCESS;
  }
  
}
