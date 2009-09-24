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

require_once(sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/modules/ullTableTool/lib/BaseUllTableToolActions.class.php');

class BaseUllUserActions extends BaseUllTableToolActions
{

  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list';
    $this->getUriMemory()->setDefault($defaultUri);  
  }  
  
  /**
   * Test for extending ullTableTool
   * @see plugins/ullCorePlugin/modules/ullTableTool/lib/BaseUllTableToolActions#executeList()
   */
  public function executeList($request)
  {
    $request->setParameter('table', 'UllUser');
    
    parent::executeList($request);
    
    $this->setTableToolTemplate('list'); 
  }
  

  /**
   * Execute show action
   */
  public function executeShow($request)
  {
    $layout = sfConfig::get('sf_root_dir') . '/plugins/ullCoreTheme' .
      sfConfig::get('app_theme_package', 'NG') .
      'Plugin/templates/emptyLayout';
    $this->setLayout($layout);
    
    $this->checkAccess('LoggedIn');
    
    $this->allow_edit = false;
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      $this->allow_edit = true;
    }
    
    $this->generator = new ullTableToolGenerator('UllUser', 'r');
    $this->getUserFromRequest();
    $this->generator->buildForm($this->user);
    
    
  }  
  
  /**
   * Test for extending ullTableTool
   * @see plugins/ullCorePlugin/modules/ullTableTool/lib/BaseUllTableToolActions#executeEdit()
   */
  public function executeEdit($request)
  {
    $request->setParameter('table', 'UllUser');
    
    parent::executeEdit($request);
    
    $this->setTableToolTemplate('edit'); 
  } 

  
  
  
  /**
   * Execute change culture
   *
   */
  public function executeChangeCulture($request)
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

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));

      if ($this->form->isValid())
      {
        $newsup = $this->form->getValue('new_superior');
        $oldsup = $this->form->getValue('old_superior');

        if ($newsup == $oldsup)
        {
          $this->redirect('ullUser/massChangeSuperiorSave?' . http_build_query(array('ok' => 0)));
        }

        $cnt = 0;
        $users = Doctrine::getTable('UllUser')->findBySuperiorUllUserId($oldsup);
        foreach ($users as $user)
        {
          $user->superior_ull_user_id = $newsup;
          $user->save();
          $cnt++;
        }
        $this->redirect('ullUser/massChangeSuperiorSave?' . http_build_query(array('ok' => 1, 'cnt' => $cnt)));
      }
    }
  }

  public function executeMassChangeSuperiorSave(sfRequest $request)
  {
    $this->checkPermission('ull_user_mass_change_superior');

    $this->ok = $request->getParameter('ok');
    if ($this->ok == 1)
    {
      $this->cnt = $request->getParameter('cnt');
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
         
        $this->getUser()->setAttribute('user_ullSearch', $search);
        $this->redirect('ullTableTool/list?query=custom&table=' . $this->modelName);
      }
    }
  }
  
  /**
   * Execute login
   *
   * @param unknown_type $request
   */
  public function executeLogin($request)
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
  public function executeNoaccess($request)
  {
//    var_dump($this->getUser()->getAttributeHolder()->getAll());die;


    
    if (!$this->getUser()->hasAttribute('user_id'))
    {
      $request->setParameter('option', 'noaccess');
      $this->forward('ullUser', 'login');
    }
  }
  
  /**
   * Shortcut method to set a template of ullTableTool
   * @param string $name      name of the template. Examples: "list", "edit", ...
   */
  protected function setTableToolTemplate($name)
  {
    $this->setTemplate(sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/modules/ullTableTool/templates/' . $name);    
  }
  
  
  /**
   * Get user by username from request
   * @return none
   */
  protected function getUserFromRequest()
  {
    
    $this->user = Doctrine::getTable('UllUser')->findOneByUsername($this->getRequestParameter('username'));
  }

  
  /**
   * Handles breadcrumb for search
   */
  protected function breadcrumbForSearch()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $this->breadcrumbTree->add(__('Advanced search'), 'ullUser/search');
  }
}
