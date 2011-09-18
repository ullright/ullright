<?php

/**
 * user actions.
 * 
 * This action extends ullTableTool to add some specific functionality
 * for UllUser without polluting ullTableTool
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllUserActions extends BaseUllGeneratorActions
{  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkAccess(array('MasterAdmins', 'UserAdmins'));
    
    $this->loadNamedQueries();
    
    parent::executeList($request);
    
    
    $this->redirectToEditIfSingleResult();

    $this->setTableToolTemplate('list');
  }  
  
  /**
   * Setup ullUserGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullUserGenerator('r', 'list', $this->columns);
  }
  
  
  /**
   * Apply custom modifications to the query
   *
   * This function builds a query selecting UllUsers for the phone book;
   * see inline comments for further details.
   */
  protected function modifyQueryForFilter()
  {
    // _                    _
    //| |                  | |
    //| |__   _____   ____ | |  _
    //|  _ \ (____ | / ___)| |_/ )
    //| | | |/ ___ |( (___ |  _ (
    //|_| |_|\_____| \____)|_| \_)
    //
    // why do we need to add this here?
    //
    // problem query like:
    // ->from('UllUser u, u.UllLocation l, u.UllCompany c)
    // ->select('u.last_name, l.name, c.name)
    // will throw exception
    // adding u.ull_location_id and u.ull_company_id does not help
    //
    // why does adding u.* resolve this?

    //the following select includes phone and fax extensions, but overrides
    //the columns with a dash if the matching boolean is false
    $this->q->getDoctrineQuery()->addSelect('x.*,');
    
    //filter per entity
    if ($id = $this->filter_form->getValue('id'))
    {
      $this->q->addWhere('x.id = ?', $id);
      $this->user = Doctrine::getTable('UllUser')->findOneById($id);
      
      $this->ull_filter->add('filter[id]', __('Owner', null, 'common') . ': ' . $this->user);
    }
    else
    {
      $this->user = null;
    }       
  }
    
  
  /**
   * Execute show action
   */
  public function executeShow(sfRequest $request)
  {
    $this->checkPermission('ull_user_show');
    
    $this->allow_edit = false;
    
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      $this->allow_edit = true;
    }
    
    $this->show_orgchart_link = false;
    {
      if (UllUserTable::hasPermission('ull_orgchart_list'))
      {
        $this->show_orgchart_link = true;
      }
    }
    
    $this->getUserFromRequestOrCreate();
    
    $this->generator = new ullTableToolGenerator(get_class($this->user), 'r');
    $this->handlePublicAccess();
    
    
    // Allow only existing users;
    if (!$this->user->exists())
    {
      $this->forward404();
    }    
    
    
    if (get_class($this->user) == 'UllUser')
    {
      $this->is_user = true;
    }
    else
    {
      $this->is_user = false;
      $this->show_orgchart_link = false;
    }
    
    $this->generator->buildForm($this->user);
    
    $this->setVar('generator', $this->generator, true);
    
    $this->location_generator = new ullTableToolGenerator('UllLocation', 'r');
    $this->location_generator->buildForm($this->user->UllLocation);
    $this->location_generator->getForm()->getWidgetSchema()->setLabel('name', __('Location', null, 'common'));
    
    $this->setVar('location_generator', $this->location_generator, true);
    
    $this->org_chart_link_user_id = (($this->user->isSuperior()) ?  $this->user->id : ($this->user->superior_ull_user_id) ? $this->user->superior_ull_user_id : $this->user->id);

    $this->setEmptyLayout();
  }  
    
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkAccess(array('MasterAdmins', 'UserAdmins'));
    
    parent::executeEdit($request);
    
    if ($password = $this->generator->getForm()->getDefault('password'))
    {
      $this->generator->getForm()->setDefault('password_confirmation', '********');
    }

    $this->setTableToolTemplate('edit');
  }  
  
  
  /**
   * Executes registration of new users 
   *
   * @param sfWebRequest $request
   */
  public function executeSignUp(sfRequest $request) 
  {
    // Check if sign up functionality is enabled
    if (!sfConfig::get('app_ull_user_enable_sign_up', false))
    {
      $this->forward404();
    }    
    
    if (UllUserTable::hasGroup('Logged In'))
    {
      $this->redirect('ullUser/editAccount');
    }

    $this->user = new UllUser;
    
    $this->handleEditAccount($request);
    
    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());
//      var_dump($this->getRequest()->getFiles());
//      die;      
      
      if ($this->generator->getForm()->bindAndSave(
        array_merge($request->getParameter('fields'), array('id' => $this->user->id)), 
        $this->getRequest()->getFiles('fields')
      ))
      {
        $this->getUser()->setAttribute('user_id', $this->generator->getRow()->id);
        
        $this->dispatcher->notify(new sfEvent($this, 'ull_user.post_sign_in', array(
          'user'        => $this->generator->getRow(),
        )));
        
        $this->sendSignUpEmail();
        
        $this->redirect('ullUser/signedUp');
      }
    }      
  }    
  
  
  /**
   * Request login details
   *
   * @param sfWebRequest $request
   */
  public function executeResetPassword(sfRequest $request) 
  {
    // Check if password resetting is enabled
    if (!sfConfig::get('app_ull_user_enable_reset_password', false))
    {
      $this->forward404();
    }    
    
    $resetPasswordData = $request->getParameter('resetPassword');
    
    $this->form = new ResetPasswordForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($resetPasswordData);
      
      if ($this->form->isValid())
      {
        $email = $this->form->getValue('email');
        
        $users = Doctrine::getTable('UllUser')->findByEmail($email);
        
        if (!$users)
        {
          $this->getUser()->setFlash('message', __(
            'Sorry, the given email address could not be found. Please try again.', 
            null, 'ullCoreMessages')
          );
          $this->redirect($request->getUri());
        }
        else
        {
          $this->sendResetPasswordEmail($users);
  
          $this->getUser()->setFlash('message',  __('Your login details have been sent to your email address', null, 'ullCoreMessages'));
          $this->redirect('ullUser/login');
        }
      }
    }
  }

  /**
   * Parses the link sent by resetPassword action, and if valid logs the user
   * in to allow password change
   * 
   * @param sfRequest $request
   */
  public function executeNewPassword(sfRequest $request)
  {
    $userId = $request->getParameter('s_user_id');
    $token = $request->getParameter('token');
    
    $user = UllUserTable::findById($userId);
    
    $this->forward404Unless($user, 'User not found');

    // Security check, this cannot be used for admins, because they can set access rights
    $this->forward404If(UllUserTable::hasGroup('MasterAdmins', $userId), 'Not allowed');
    $this->forward404If(UllUserTable::hasGroup('UserAdmins', $userId), 'Not allowed');
    
    if (UllUserOneTimeTokenTable::isValidAndUseUp($token, $userId))
    {
      // Token is valid, log the user in
      $this->getUser()->setAttribute('user_id', $userId);
      
      $this->redirect('ullUser/editAccount');
    }
    else
    {
      $this->getUser()->setFlash('message',
        __('Sorry, this request is invalid. Please request your login details again.', 
          null, 'ullCoreMessages')
      );
      $this->redirect('ullUser/resetPassword');
    }
    
  }
  
  
  /**
   * Executes registration of new users action
   *
   * @param sfWebRequest $request
   */
  public function executeSignedUp(sfRequest $request)
  {
    $this->checkLoggedIn();
    
    $this->return_url = '@homepage';
    
    if ($this->getUriMemory()->has('login', 'ullUser'))
    {
      $this->return_url = $this->getUriMemory()->getAndDelete('login', 'ullUser');
    }
  } 
  
  
  /**
   * Executes the edit my account action
   *
   * @param sfWebRequest $request
   */
  public function executeEditAccount(sfRequest $request)
  {
    $this->checkLoggedIn();
    
    $this->user = UllUserTable::findLoggedInUser();
    
    $this->forward404Unless($this->user);
    
    $this->handleEditAccount($request);
    
    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());
//      var_dump($this->getRequest()->getFiles());
//      die;      
      
      if ($this->generator->getForm()->bindAndSave(
        array_merge($request->getParameter('fields'), array('id' => $this->user->id)), 
        $this->getRequest()->getFiles('fields')
      ))
      {
        $this->getUser()->setFlash('message', __('Your changes have been saved', null, 'common'));
        $this->redirect($request->getUri());
      }
    }     
  }
  
  /**
   * Sets the status of a user to inactive
   *
   * @param sfWebRequest $request
   */
  public function executeSetInactive(sfRequest $request)
  {
    $inactiveUser = UllUserTable::findLoggedInUser();
    if(isset($inactiveUser))
      $inactiveUser['ull_user_status_id'] = Doctrine::getTable('UllUserStatus')->findOneBySlug('inactive')->id;
      $inactiveUser->save();
      
      $this->logoutIfLoggedIn(); 
      
      $this->getUser()->setFlash('message',  __('Your account is inactive', null, 'ullCoreMessages'));
      $this->redirect('ullUser/login');
  } 
  
  
  /**
   * Common functionality for registering / account editing
   *  
   * @param sfRequest $request
   */
  protected function handleEditAccount(sfRequest $request)
  {
    $this->generator = $this->getEditGenerator();
    
    $columnsConfig = $this->generator->getColumnsConfig();
    $columnsConfig->adjustColumnConfigForEditAccount($this->user);
    
    $this->generator->buildForm($this->user);

    $this->addDuplicateUsernameErrorMessage();
    
    if ($password = $this->generator->getForm()->getDefault('password'))
    {
      $this->generator->getForm()->setDefault('password_confirmation', '********');
    }        
    
//    $this->generator->getForm()->debug();die;
    
    $this->setVar('generator', $this->generator, true);  

    $this->setVar('form_uri', $this->getEditFormUri(), true);
  }
  
  
  /**
   * Add a better error message for a duplicate username
   * 
   * Actual the code here is a bit fuzzy, as we haven't found a way
   * to specificly access the "unique" validator for the username
   */
  protected function addDuplicateUsernameErrorMessage()
  {
    $postValidator = $this->generator->getForm()->getValidatorSchema()->getPostValidator();

    // If a getValidators() method exist we assume we have multiple post
    // validators
    if (method_exists($postValidator, 'getValidators'))
    {
      $validators = $postValidator->getValidators();
    }
    else
    {
      $validators[] = $postValidator;  
    }
    
    // Find the "unique" validator
    // TODO: be more specific in case we have multiple unique validators
    foreach ($validators as $validator)
    {
      if ($validator instanceof sfValidatorDoctrineUnique)
      {
        $validator->setMessage('invalid', __('The username is given away. Please choose another one', null, 'common'));
      }
    }    
  }
  
  
  /**
   * Disable columns which should not be seen by unpriviledged users
   * 
   * @return none
   */
  protected function handlePublicAccess()
  {
    if (!UllUserTable::hasGroup('MasterAdmins') && !UllUserTable::hasGroup('UserAdmins'))
    {
      $this->generator->getColumnsConfig()->disableAllExcept(
        sfConfig::get('app_ull_user_user_popup_public_columns', array(
          'last_name',
          'first_name',
          'email',
          'photo',
          'type',
          'ull_company_id',
          'ull_department_id',
          'ull_job_title_id',
          'superior_ull_user_id',
          'phone_extension',
          'fax_extension',
          'mobile_number',
          'ull_user_status_id',
        ))
      );
    }    
  }
  
  
  /**
   * Setup ullUserGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullUserGenerator('w');
  }  
  
  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDelete(sfRequest $request)
  { 
    $this->checkAccess('MasterAdmins');
    
    parent::executeDelete($request);
  }  
  

  /**
   * Setup ullUserGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator() 
  { 
    return new ullUserGenerator();
  }
  
  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDeleteFutureVersion(sfRequest $request)
  { 
    $this->checkAccess('MasterAdmins');
    
    parent::executeDeleteFutureVersion($request);
  }

  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullUserFilterForm';
  }  
  

  /**
   * Execute change culture
   *
   */
  public function executeChangeCulture(sfRequest $request)
  {
    $this->getUriMemory()->setReferer();

    //TODO: shouldn't we match against app_i18n_supported_languages here?
    
    //set the culture for the user object (which is always available)
    $this->getUser()->setCulture($request->getParameter('culture'));
    
    //and set it in the user's record (only available if a user is logged-in)
    if (($user = UllUserTable::findLoggedInUser()) !== false)
    {
      $user['selected_culture'] = $request->getParameter('culture');
      $user->save();
    }
    
    // the following flag is used by the i18n filter to detect manual setting of culture
    $this->getUser()->setAttribute('is_culture_set_by_user', true);
    
    $this->redirect($this->getUriMemory()->getAndDelete());
  }

  
  /**
   * Execute mass change superior action
   * 
   * @param $request
   * @return unknown_type
   */
  public function executeMassChangeSuperior(sfRequest $request)
  {
    $this->checkPermission('ull_user_mass_change_superior');

    $this->form = new ullMassChangeSuperiorForm();
    
    $this->breadcrumbForMassChangeSuperior();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));

      if ($this->form->isValid())
      {
        $newsup = $this->form->getValue('new_superior');
        $oldsup = $this->form->getValue('old_superior');

        $count = 0;
        $users = Doctrine::getTable('UllUser')->findBySuperiorUllUserId($oldsup);
        foreach ($users as $user)
        {
          if ($user->id == $newsup)
          {
            //prevent users from becoming their own superior
            continue;
          }
          $user->superior_ull_user_id = $newsup;
          $user->save();
          $count++;
        }
  
        if ($count)
        {
          $this->getUser()->setFlash('message',
            format_number_choice('[1]The superior was successfully replaced for one user.' .
              '|(1,+Inf]The superior was successfully replaced for %1% users.',
              array('%1%' => $count), $count)
          );
        }
        else
        {
          $this->getUser()->setFlash('message',        
            __('There are no subordinated users for the given superior.')
          );
        }
        
        $this->redirect('ullUser/massChangeSuperior');
      }
    }
  }

  
  //          _______  _______  _______  _______  _______
  //        (  ____ \(  ____ \(  ___  )(  ____ )(  ____ \|\     /|
  //        | (    \/| (    \/| (   ) || (    )|| (    \/| )   ( |
  //        | (_____ | (__    | (___) || (____)|| |      | (___) |
  //        (_____  )|  __)   |  ___  ||     __)| |      |  ___  |
  //              ) || (      | (   ) || (\ (   | |      | (   ) |
  //        /\____) || (____/\| )   ( || ) \ \__| (____/\| )   ( |
  //        \_______)(_______/|/     \||/   \__/(_______/|/     \|
  //
  
  /**
   * This function builds a search form utilizing the ull search
   * framework, see the individual classes for reference.
   * If it handles a post request, it builds the actual search object
   * and forwards to the list action.
   * 
   * @param $request The current request
   */
  public function executeSearch(sfRequest $request)
  {
    $this->moduleName = $request->getParameter('module');
    $this->modelName = 'UllUser';
    $this->getUriMemory()->setUri('search');
    $this->breadcrumbForSearch();
    $searchConfig = ullSearchConfig::loadSearchConfig('ullUser');

    $fieldParameter = ullSearchActionHelper::removeReqpassHelperString($request->getParameter('fields'));
    $doRebind = ullSearchActionHelper::handleAddOrRemoveCriterionButtons($request, $this->getUser());

    $searchGenerator = new ullSearchGenerator($searchConfig->getAllSearchableColumns(), $this->modelName);
    $this->addCriteriaForm = new ullSearchAddCriteriaForm($searchConfig, $searchGenerator);
    $searchFormEntries = ullSearchActionHelper::retrieveSearchFormEntries($this->moduleName, $searchConfig, $this->getUser());
    $searchGenerator->reduce($searchFormEntries);
    $this->searchForm = new ullSearchForm($searchGenerator);

    $isSubmit = ($request->isMethod('post') && $this->getRequestParameter('searchSubmit'));
    if (isset($doRebind) || $isSubmit)
    {
      $this->searchForm->getGenerator()->getForm()->bind($fieldParameter);

      if ($isSubmit && $this->searchForm->getGenerator()->getForm()->isValid())
      {
        $search = new ullSearch();
        ullSearchActionHelper::addTransformedCriteriaToSearch($search, $searchFormEntries,
          $this->searchForm->getGenerator()->getForm()->getValues());
         
        $this->getUser()->setAttribute('ullUserGenerator_ullSearch', $search);
        $this->redirect('ullUser/list?query=custom');
      }
    }
  }
  
  
  /**
   * Execute login
   *
   * @param unknown_type $request
   */
  public function executeLogin(sfRequest $request)
  {
//    var_dump($this->getUser()->getAttributeHolder()->getAll());
//    var_dump($this->getRequest()->getParameterHolder()->getAll());

    $this->performCookieCheck($request);

    $this->form = new LoginForm();
    
    $loginData = $request->getParameter('login');
    
    // Check context. The actual submission of the login data is marked by the
    // parameter "login_request". Usually this context check is performed by
    // checking for GET or POST method. This is not possible here because of
    // the functionality to prevent data loss of form submissions in case the
    // session timed out
    if (!isset($loginData['login_request']))
    {
      // Stores POST values of the last request to prevent data loss when the session timed out
      if ($request->isMethod('post'))
      {
        $this->form->setDefault('original_request_params', urlencode(serialize($this->getRequest()->getParameterHolder()->getAll())));
      }
      
      // Handle forwarded request by an access check action
      if ($request->getParameter('option') == 'noaccess')
      {
        $this->getUser()->setFlash('message',  __('Please login to verify access'));
        $uri = $this->getUriMemory()->getAndDelete('noaccess', 'ullUser');
        $this->getUriMemory()->setUri('login', 'ullUser', true, $uri); 
      }
      
      return sfView::SUCCESS;
    }
    
    // Normal login form submission functionality
    else
    {
      $this->form->bind($loginData);

      if ($this->form->isValid())
      {
        // handle the form submission
        $username = $this->form->getValue('username');
        $password = $this->form->getValue('password');
        $jsCheck = $this->form->getValue('js_check');

        // remember javascript support
        $this->getUser()->setAttribute('has_javascript', false);
        if ($jsCheck == 1) 
        {
          $this->getUser()->setAttribute('has_javascript', true);
        }

        // Find user by username
        $user = Doctrine::getTable('UllUser')->findOneByUsername($username);
        
        if ($user)
        {
          //        echo md5($password) . ' - ' . $user->password;

          // authenticate
          $auth_class = 'ullAuth'
            . sfInflector::classify(sfConfig::get('app_auth_function', 'default'));

          if (call_user_func($auth_class . '::authenticate', $user, $password))
          {
            if (!($user->UllUserStatus->getIsActive()))
            {
              $this->getUser()->setFlash('message', 
                __('This user account is marked as inactive, please contact your administrator.'));
                
              return;
            }
            else
            {
              $this->getUser()->setAttribute('user_id', $user->getId());      

              //if the user has a set culture, use it
              if ($user['selected_culture'] !== null)
              {
                //it might happen that a user has a culture set which is not supported (anymore)
                //in this case, reset the culture to the default one
                $supportedLanguages = sfConfig::get('app_i18n_supported_languages', array('de', 'en'));
                if (!in_array($user['selected_culture'], $supportedLanguages))
                {
                  $user['selected_culture'] = sfConfig::get('sf_default_culture');
                  $user->save();
                }
                
                $this->getUser()->setCulture($user['selected_culture']);
                //tell the i18n filter that we already have a culture available
                $this->getUser()->setAttribute('is_culture_set_by_user', true);
              }
              
              // Restore original POST request values and forward
              if ($originalRequestParams = $this->form->getValue('original_request_params'))
              {
                $originalRequestParams = unserialize(urldecode($originalRequestParams));
                $this->getRequest()->getParameterHolder()->remove('login');
                $this->getRequest()->getParameterHolder()->remove('commit');
                
                foreach ($originalRequestParams as $name => $param)
                {
                  $request->setParameter($name, $param);
                }
                
                $this->getUriMemory()->delete();
  	            $this->forward($request->getParameter('module'), $request->getParameter('action'));
              }
              // Use UriMemory and redirect to the source URI 
              else
              {
                $this->redirect($this->getUriMemory()->getAndDelete()); 
              }
	          }
          }
        }
      }

      $this->getUser()->setFlash('message',  __('Login failed. Please try again:'), false);
    }
  }

  
  /**
   * Execute logout
   *
   */
  public function executeLogout()
  {
    foreach ($this->getUser()->getAttributeHolder()->getAll() as $key => $value)
    {
      $this->getUser()->getAttributeHolder()->remove($key);
    }
    
    $this->redirect('@homepage');
  }
  
  
  /**
   * Execute no access action
   *
   */
  public function executeNoaccess(sfRequest $request)
  {
//    var_dump($this->getUser()->getAttributeHolder()->getAll());die;
    
    if (!$this->getUser()->hasAttribute('user_id'))
    {
      $request->setParameter('option', 'noaccess');
      $this->forward('ullUser', 'login');
    }
  }
  
  
  /**
   * Display error msg in case of disabled cookies
   * 
   * @param sfRequest $request
   */
  public function executeNoCookies(sfRequest $request)
  {
    $this->message = __('Login not possible. Please activate cookies in your browser.', null, 'ullCoreMessages');
  }
  
  
  /**
   * Get user by username from request
   * @return none
   */
  protected function getUserFromRequestOrCreate()
  {
    $username = $this->getRequestParameter('username');
    
    if ($username)
    {
      // also allow the id for bc compatibility
      if (is_numeric($username))
      {     
        $this->user = Doctrine::getTable('UllEntity')->find($username);
      }
      else
      {
        $this->user = Doctrine::getTable('UllEntity')->findOneByUsername($username);
      }
      
      if (!$this->user)
      {
        $this->forward404();
      }
    }
    else
    {
      $this->user = new UllUser;
    }
  }
  
  /**
   * Toggles the 'hide sidebar' session variable and returns
   * 0 if the sidebar is now hidden or 1 otherwise.
   *
   * This method usually gets called via AJAX from the sidebar script. 
   */
  public function executeToggleSidebar(sfRequest $request)
  {
    $hideSidebar = $this->getUser()->getAttribute('sidebar_hidden', false);
    
    $hideSidebar = !$hideSidebar;
    
    $this->getUser()->setAttribute('sidebar_hidden', $hideSidebar);
    
    return $this->renderText((int)$hideSidebar);
  }
  
  public function executeUserSearchAutocomplete(sfRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    
    $queryString = $request->getParameter('q');
    $queryLimit = $request->getParameter('limit');
    
    //is concat mysql only?
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u')
      ->where('concat(u.last_name, \' \', u.first_name) like ?', '%' . $queryString . '%')
      ->orWhere('concat(u.first_name, \' \', u.last_name) like ?', '%' . $queryString . '%')
      ->orderBy('u.last_name')
      ->limit($queryLimit)
    ;
    
    $results = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    $users = array();
    foreach ($results as $user)
    {
      $users[$user['id']] = $user['last_name'] . ' ' . $user['first_name'];
    }
    
    return $this->renderText(json_encode($users));
  }  
  
  
  /**
   * Shortcut: redirect directly to edit action if we have a single result
   * 
   * @return none
   */
  protected function redirectToEditIfSingleResult()
  {
    if (count($this->docs) == 1 && $this->getRequestParameter('single_redirect', 'true') == 'true')
    {
      $this->getUser()->setFlash('message', __(
        'Redirected from the result list because there was only a single result. Click on "result list" in the breadcrumb navigation above to return to the list view.', 
        null, 'common'));
      $this->getUriMemory()->append('single_redirect=false');
      $this->redirect('ullUser/edit?id=' . $this->docs[0]->id);
    }
  }  
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('Users', null, 'ullCoreMessages'));
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullUser/list');
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('Users', null, 'ullCoreMessages'));
    
    // display result list link only when there is a referer containing 
    //  the list action 
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumb_tree->addDefaultListEntry();
    }    
    
//    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullUser/list');  
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
   * Handles breadcrumb for search
   */
  protected function breadcrumbForSearch()
  {
    $breadcrumbTree = new breadcrumbTree();
    $breadcrumbTree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumbTree->add(__('Advanced search', null, 'common'), 'ullUser/search');
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Handles breadcrumb for mass change superior
   */
  protected function breadcrumbForMassChangeSuperior()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Superior mass change'));
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }

  
  /**
   * Logout if logged in
   */
  protected function logoutIfLoggedIn()
  {
    if ($this->getUser()->getAttribute('user_id'))
    {
      $this->getUser()->setAttribute('user_id', null);
    }   
  }
  
  
  /**
   * Send registration email
   */
  protected function sendSignUpEmail()
  {
    $object = $this->generator->getRow();
    $name = $object->first_name . ' ' . $object->last_name;
    
    $mail = new ullsfMail('ull_user_signed_up');

    $mail->setFrom(
      sfConfig::get('app_ull_user_sign_up_sender_address', 'noreply@example.com'),
      sfConfig::get('app_ull_user_sign_up_sender_name', 'No reply')
    );
    $mail->addAddress(
      $object->email, 
      $name
    );
    $mail->setSubject(__('Your account data', null, 'ullCoreMessages'));
    
    $mail->setBody(__('Hello %name%,

Here\'s your account data for %home_url%
    
Username: %username%

You can edit your account at %edit_account_url%
', array(
  '%name%'              => $name,
  '%home_url%'          => url_for('@homepage', true),
  '%username%'          => $object->username,
  '%password%'          => $_POST['fields']['password'],
  '%edit_account_url%'  => url_for('ullUser/editAccount', true),
    ), 'ullCoreMessages'));
    
    $mail->send();
  }
  
  
/**
   * Send email for reset password
   */
  protected function sendResetPasswordEmail($users)
  {
    // Multiple users can have the same email address e.g. parent and children.
    // We send one email with all the users included
    if ($users instanceof UllUser)
    {
      $firstUser = $users;
    }
    else
    {
      $firstUser = $users->getFirst();
    }
    
    $mail = new ullsfMail();

    $mail->setFrom(
      sfConfig::get('app_ull_user_reset_password_sender_address', 'noreply@example.com'),
      sfConfig::get('app_ull_user_reset_password_sender_name', 'No reply')
    );
    $mail->addAddress(
      $firstUser->email
    );
    
    $mail->usePartial('ullUser/resetPasswordMail', array('users' => $users), true);
    
    $mail->send();
  }
  
  
  /**
   * Perform cookie check upon login
   * @param sfRequest $request
   */
  protected function performCookieCheck(sfRequest $request)
  {
    if ($request->isMethod('get'))
    {
      // initialize cookie check
      if (!$request->getParameter('cookie_check'))
      {
        $this->getResponse()->setCookie('cookie_check', 1);
        
        //pass down an optional flash message
        $this->getUser()->setFlash('message', $this->getUser()->getFlash('message'));
        // Save current referer
        $this->getUriMemory()->setReferer(null, null, false);
        
        $this->ull_redirect(array('cookie_check' => 'true'));
      }
      
      // perform cookie check
      else
      {
        if (!$this->getRequest()->getCookie('cookie_check'))
        {
          $this->redirect('ullUser/noCookies');
        }
      }
    }    
  }
  
  /**
   * Load named queries for index and list action
   * 
   * @return none
   */
  protected function loadNamedQueries()
  {
    $this->setVar('named_queries', new ullNamedQueriesUllUser, true);
    
    if (class_exists('ullNamedQueriesUllUserCustom'))
    {
      $this->setVar('named_queries_custom', new ullNamedQueriesUllUserCustom(), true);
    }
    else
    {
      $this->named_queries_custom = null;
    }
  }  
}
