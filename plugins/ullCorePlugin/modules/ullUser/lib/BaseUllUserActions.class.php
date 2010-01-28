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
    
    $this->setVar('named_queries', new ullNamedQueriesUllUser, true);
    
    parent::executeList($request);

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
    
    $this->generator = new ullTableToolGenerator('UllEntity', 'r');
    $this->handlePublicAccess();
    $this->getUserFromRequest();
    
    $this->generator->buildForm($this->user);
    
    $this->setVar('generator', $this->generator, true);
    
    $this->location_generator = new ullTableToolGenerator('UllLocation', 'r');
    $this->location_generator->buildForm($this->user->UllLocation);
    $this->location_generator->getForm()->getWidgetSchema()->setLabel('name', __('Location', null, 'common'));
    
    $this->setVar('location_generator', $this->location_generator, true);

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

    $this->setTableToolTemplate('edit');
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
        
    $this->getUser()->setCulture($request->getParameter('culture'));
    
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

    $doRebind = ullSearchActionHelper::handleAddOrRemoveCriterionButtons($request, $this->getUser());

    $searchGenerator = new ullSearchGenerator($searchConfig->getAllSearchableColumns(), $this->modelName);
    $this->addCriteriaForm = new ullSearchAddCriteriaForm($searchConfig, $searchGenerator);
    $searchFormEntries = ullSearchActionHelper::retrieveSearchFormEntries($this->moduleName, $searchConfig, $this->getUser());
    $searchGenerator->reduce($searchFormEntries);
    $this->searchForm = new ullSearchForm($searchGenerator);

    $isSubmit = ($request->isMethod('post') && $this->getRequestParameter('searchSubmit'));
    if (isset($doRebind) || $isSubmit)
    {
      $this->searchForm->getGenerator()->getForm()->bind($request->getParameter('fields'));

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
    
    $loginData = $request->getParameter('login');
    $this->form = new LoginForm();
    
    //check context
    if (!isset($loginData['login_request']))
    {
      // Stores POST values of the last request to prevent data loss when the session timed out
      if ($request->isMethod('post'))
      {
        $this->form->setDefault('original_request_params', urlencode(serialize($this->getRequest()->getParameterHolder()->getAll())));
      }
      
      if ($request->getParameter('option') == 'noaccess')
      {
        $this->msg = __('Please login to verify access');
        $uri = $this->getUriMemory()->getAndDelete('noaccess', 'ullUser');
        $this->getUriMemory()->setUri('login', 'ullUser', true, $uri); 
      }
      else
      {
        $this->getUriMemory()->setReferer(null, null, false);
      }
      
      return sfView::SUCCESS;
    }
    else
    {
//      var_dump(sfContext::getInstance()->getUser()->getAttributeHolder()->getAll());die;
      $this->form->bind($loginData);

      if ($this->form->isValid())
      {
        // handle the form submission
        $username = $this->form->getValue('username');
        $password = $this->form->getValue('password');
        $jsCheck = $this->form->getValue('js_check');

        //user has javascript enabled?
        $this->getUser()->setAttribute('has_javascript', false);
        if ($jsCheck == 1) 
        {
          $this->getUser()->setAttribute('has_javascript', true);
        }

        $user = Doctrine::getTable('UllUser')->findOneByUsername($username);

        if ($user !== false)
        {
          //        echo md5($password) . ' - ' . $user->password;

          // authenticate
          $auth_class = 'ullAuth'
            . sfInflector::classify(sfConfig::get('app_auth_function', 'default'));

          if (call_user_func($auth_class . '::authenticate', $user, $password))
          {
            if (!($user->UllUserStatus->getIsActive()))
            {
              $this->msg = __('This user account is marked as inactive, please contact your administrator.');
              return;
            }
            else
            {
              $this->getUser()->setAttribute('user_id', $user->getId());      

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

      $this->msg = __('Login failed. Please try again:');
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
   * Get user by username from request
   * @return none
   */
  protected function getUserFromRequest()
  {
    $username = $this->getRequestParameter('username');
    
    // also allow the id for bc compatibility
    if (is_numeric($username))
    {     
      $this->user = Doctrine::getTable('UllEntity')->find($username);
    }
    else
    {
      $this->user = Doctrine::getTable('UllEntity')->findOneByUsername($username);
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
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullUser/list');  
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
    $breadcrumbTree->add(__('Advanced search'), 'ullUser/search');
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
}
