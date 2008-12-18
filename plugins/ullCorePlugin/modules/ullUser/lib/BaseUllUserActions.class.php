<?php

/**
 * user actions.
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllUserActions extends ullsfActions
{
  /**
   * Execute change culture
   *
   */
  public function executeChangeCulture() 
  {
    $culture = $this->getRequestParameter('culture');
    $this->getUser()->setCulture($culture);
    // the following flag is used by the i18n filter to detect manuall setting of culture
    $this->getUser()->setAttribute('is_culture_set_by_user', true);
    $this->redirect($this->getUser()->getAttribute('referer'));
  }  
  
  /**
   * Execute login
   *
   * @param unknown_type $request
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
        $this->msg = __('Please login to verify access');
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
  
        $user = Doctrine::getTable('UllUser')->findByUsername($username)->getFirst();
        
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
      
      $this->msg = __('Login failed. Please try again:'); 
//      return sfView::SUCCESS;
    }
  }
  
  /**
   * Execute logout
   *
   */
  public function executeLogout() 
  {
    $this->getUser()->setAttribute('user_id', 0);
    $this->getUser()->setAttribute('has_javascript', false);
    $this->redirect('@homepage');
  }

  /**
   * ??
   *
   * @return unknown
   */
  public function handleErrorLogin() 
  {
    return sfView::SUCCESS;
  }
  
  /**
   * Execute no access action
   *
   */
  public function executeNoaccess() 
  {
    $this->refererHandler = new refererHandler();
    
    if (!$this->getUser()->getAttribute('user_id')) 
    {
      $this->refererHandler->initialize('login');
      return $this->redirect('ullUser/login?option=noaccess');
    }    

    $this->refererHandler->initialize('access');
    $referer = $this->refererHandler->getRefererAndDelete('access');
    $this->referer = $referer ? $referer : '@homepage';
    return sfView::SUCCESS;
  }
  
}
