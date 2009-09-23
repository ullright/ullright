<?php

/**
 * ullTime actions.
 *
 * @package    ullright
 * @subpackage ullTime
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllTimeActions extends ullsfActions
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
    
    //Add ullTime stylsheet for all actions
    $path =  '/ullTimeTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }  
  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex() 
  {
    $this->checkAccess('LoggedIn');

    $this->breadcrumbForIndex();
    
    $this->periods = UllTimePeriodTable::findCurrentAndPast();
  }
  
  
  /**
   * Execute list action
   * 
   * @param $request
   * @return unknown_type
   */
  public function executeList($request)
  {
    $this->checkAccess('LoggedIn');
    
    $this->getPeriodFromRequest();
    $this->getUserFromRequest();
    
    $this->dates = $this->period->getDateList();
    
    $dateWidget = new ullWidgetDateRead(array('show_weekday' => true));
    $timeDurationWidget = new ullWidgetTimeDurationRead();
    
    foreach($this->dates as $date => $day)
    {
      if ($date <= date('Y-m-d'))
      {
        $this->dates[$date]['humanized_date'] = $dateWidget->render(null, $date);
        
        $sumProject = UllProjectReportingTable::findSumByDateAndUserId($date, $this->user_id);  
        $this->dates[$date]['sum_project'] = $timeDurationWidget->render(null, $sumProject);
        
        $sumTime = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($date, $this->user_id);
        $this->dates[$date]['sum_time'] = $timeDurationWidget->render(null, $sumTime);
      }
      else
      {
        unset($this->dates[$date]);
      }
    }
    
    rsort($this->dates);
    
    $this->breadcrumbForList();
  }
  
  
  /**
   * Execute create action
   * 
   */  
  public function executeCreate()
  {
    $this->forward('ullTime', 'edit');
  }
  
   /**
   * Execute edit action
   * 
   */
  public function executeEdit($request) 
  {
    $this->checkAccess('LoggedIn');
    
    $this->getDocFromRequestOrCreate();
    
    $this->generator = new ullTableToolGenerator('UllTimeReporting', 'w');
    $this->generator->buildForm($this->doc);
    
    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
//      var_dump($_REQUEST);
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
//      die;
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        $this->redirect($this->getUriMemory()->getAndDelete('list'));
      }
      else
      {
//        var_dump($this->generator->getForm()->getErrorSchema());
      }
    }
//    echo $this->generator->getForm()->debug();
  }
  
  
    
  /**
   * Execute create action
   * 
   */
  public function executeCreateProject() 
  {
    $this->forward('ullTime', 'editProject');
  } 

  
  /**
   * Execute edit action
   * 
   */
  public function executeEditProject($request) 
  {
    $this->checkAccess('LoggedIn');
    
    $this->getProjectReportingFromRequestOrCreate();
    
    $this->list_generator = new ullTableToolGenerator('UllProjectReporting', 'r', 'list');
    $this->list_generator->buildForm($this->docs);
    
    $this->edit_generator = new ullTableToolGenerator('UllProjectReporting', 'w');
    $this->edit_generator->buildForm($this->doc);
    
    $this->breadcrumbForEditProject();
    
    if ($request->isMethod('post'))
    {
//      var_dump($_REQUEST);
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
//      die;
      if ($this->edit_generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        if ($request->getParameter('action_slug') == 'save_new') 
        {
          $this->redirect('ullTime/createProject?date=' . $request->getParameter('date') . '&username=' . $request->getParameter('username'));
        } 
        // use the default referer
        else
        {
          $this->redirect($this->getUriMemory()->getAndDelete('list'));
        }
      }
      else
      {
//        var_dump($this->generator->getForm()->getErrorSchema());
      }
    }
//    echo $this->generator->getForm()->debug();
  }


  /**
   * Delete a project effort
   * 
   * @return none
   */
  public function executeDeleteProject($request)
  {
    $this->checkAccess('LoggedIn');
    $this->getProjectReportingFromRequestOrCreate();
    $this->doc->delete();
    $this->redirect('ullTime/createProject?date=' . $request->getParameter('date') . '&username=' . $request->getParameter('username'));
  }
  
  
  /** 
   * Get timeReport according to request params
   * @return unknown_type
   */
  protected function getDocFromRequestOrCreate()
  {
    $this->getDateFromRequest();
    $this->getUserFromRequest();
    
    $this->doc = UllTimeReportingTable::findByDateAndUserId($this->date, $this->user->id);
    
    if ($this->getRequestParameter('action') == 'create')
    {
      if ($this->doc)
      {
        $this->redirect('ullTime/edit?date=' . $this->date . '&username=' . $this->user->username);
      }
      else
      {
        $this->doc = new UllTimeReporting;
        $this->doc->ull_user_id = $this->user->id;
        $this->doc->date = $this->date;      
      }
    }
  }
  
  
  /**
   * Gets projectReports according to request params
   * 
   */
  protected function getProjectReportingFromRequestOrCreate()
  {
    $this->getDateFromRequest();
    $this->getUserFromRequest();
    
    $this->docs = UllProjectReportingTable::findByDateAndUserId($this->date, $this->user_id);
    
    if ($this->getRequestParameter('action') == 'createProject')
    {
      $this->doc = new UllProjectReporting;
      $this->doc->ull_user_id = $this->user->id;
      $this->doc->date = $this->date;      
    }
    else
    {
      $this->forward404Unless($this->doc = Doctrine::getTable('UllProjectReporting')->findOneById($this->getRequestParameter('id')));
    }
  }
  
  
  /**
   * Get date from Request
   * 
   * @return unknown_type
   */
  protected function getDateFromRequest()
  {
    $this->date = $this->getRequestParameter('date');
    
    if (!$this->date)
    {
      $this->date = date('Y-m-d');
      $this->getRequest()->setParameter('date', $this->date);
    }
  }  
    
  /**
   * Get user from request
   * 
   * @return none
   */
  protected function getUserFromRequest()
  {
    $username = $this->getRequestParameter('username');
    if (!$username)
    {
      $username = UllUserTable::getLoggedInUsername();
      $this->getRequest()->setParameter('username', $username);
    }
    
    $this->user = Doctrine::getTable('UllUser')->findOneByUsername($username);
    $this->user_id = $this->user->id;
    
    // Access check
    if (!UllUserTable::hasGroup('MasterAdmins'))
    {
      $this->redirectToNoAccessUnless($this->user->id == $this->getUser()->getAttribute('user_id'));
    }
  }
  
  
  /**
   * Get the period from request
   * 
   * @return unknown_type
   */
  protected function getPeriodFromRequest()
  {
    $slug = $this->getRequestParameter('period_slug');
    if (!$slug)
    {
      $slug = UllTimePeriodTable::findSlugByDate(date('Y-m-d'));
    }  
    $this->period = Doctrine::getTable('UllTimePeriod')->findOneBySlug($slug);
  }
  
  
  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $this->breadcrumbTree = new ullTimeBreadcrumbTree();
  }
  
  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() 
  {
    $this->breadcrumbTree = new ullTimeBreadcrumbTree;
    $this->breadcrumbTree->addDefaultListEntry();
  }  

  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() 
  {
    $this->breadcrumbTree = new ullTimeBreadcrumbTree;
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->addDefaultListEntry();

    if ($this->doc->exists()) 
    {
      $this->breadcrumbTree->add(__('Edit time report', null, 'ullTimeMessages'));
    } 
    else 
    {
      $this->breadcrumbTree->add(__('Create time report', null, 'ullTimeMessages'));
    } 
  } 

  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEditProject() 
  {
    $this->breadcrumbTree = new ullTimeBreadcrumbTree;
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->addDefaultListEntry();

    if ($this->doc->exists()) 
    {
      $this->breadcrumbTree->add(__('Edit project effort', null, 'ullTimeMessages'));
    } 
    else 
    {
      $this->breadcrumbTree->add(__('Create project effort', null, 'ullTimeMessages'));
    } 
  }  
  

}