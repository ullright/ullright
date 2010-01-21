<?php

/**
 * ullTime actions.
 *
 * @package    ullright
 * @subpackage ullTime
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllTimeActions extends BaseUllGeneratorActions
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
  public function executeIndex(sfRequest $request) 
  {
    $this->checkPermission('ull_time_index');
    
    // clean potentially saved list view uri 
    $this->getUriMemory()->delete('list');
    
    $this->act_as_user_form = new ullTimeActAsUserForm();
    
    if ($request->isMethod('post'))
    {
      $this->act_as_user_form->bind($request->getParameter('fields'));
      
      if ($this->act_as_user_form->isValid())
      {
        $username = UllUserTable::findUsernameById($this->act_as_user_form->getValue('ull_user_id'));
        // TODO: why is the new url displayed with "?" and "=" instead of the
        // usual symfony style with "/" ? 
        $this->redirect('ullTime/index' . (($username) ? '?username=' . $username : ''));
      }
    }
    else
    {
      if ($username = $request->getParameter('username'))
      {
        $this->act_as_user_form->setDefault('ull_user_id', UllUserTable::findIdByUsername($username));
      }
    }       

    $this->breadcrumbForIndex();
    
    $this->periods = UllTimePeriodTable::findCurrentAndPast();
  }
  
  
  /**
   * Execute list action
   * 
   * @param $request
   * @return unknown_type
   */
  public function executeList(sfRequest $request)
  {
    $this->checkPermission('ull_time_list');
    
    $this->getPeriodFromRequest();
    $this->getUserFromRequest();
    
    $this->getUriMemory()->setUri();
    
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
  
  
  public function executeReportProject(sfRequest $request)
  {
    $this->checkPermission('ull_time_report');
    
    $this->generator = new ullTimeReportGenerator();
    $this->generator->setCalculateSums(true);

    $rows = $this->getFilterFromRequest();

    $this->generator->buildForm($rows);
    
    $this->setVar('generator', $this->generator, true);
  }
  
  
  /**
   * Execute create action
   * 
   */  
  public function executeCreate(sfRequest $request)
  {
    $this->forward('ullTime', 'edit');
  }
  
   /**
   * Execute edit action
   * 
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_time_edit');
    
    $this->getDocFromRequestOrCreate();
    
    $this->generator = new ullTableToolGenerator('UllTimeReporting', $this->getLockingStatus());
    $this->generator->buildForm($this->doc);
    $this->addGlobalValidators();
    
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
  public function executeCreateProject(sfRequest $request) 
  {
    $this->forward('ullTime', 'editProject');
  } 

  
  /**
   * Execute edit action
   * 
   */
  public function executeEditProject(sfRequest $request) 
  {
    $this->checkPermission('ull_time_edit_project');
    
    $this->getProjectReportingFromRequestOrCreate();
    
    $this->list_generator = new ullTableToolGenerator('UllProjectReporting', 'r', 'list');
    $this->list_generator->setCalculateSums(true);
    $this->list_generator->buildForm($this->docs);
    
    $this->edit_generator = new ullTableToolGenerator('UllProjectReporting', $this->getLockingStatus());
    $this->edit_generator->buildForm($this->doc);
    
    $this->breadcrumbForEditProject();
    
    $this->cancel_link = $this->getUriMemory()->get('list');
    
    if ($request->isMethod('post'))
    {
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
  public function executeDeleteProject(sfRequest $request)
  {
    $this->checkPermission('ull_time_delete_project'); 
    
    $this->getProjectReportingFromRequestOrCreate();
    
    $this->forward404Unless($this->getLockingStatus() == 'w');
    
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
    $slug = $this->getRequestParameter('period');
    if (!$slug)
    {
      $slug = UllTimePeriodTable::findSlugByDate(date('Y-m-d'));
    }
    
    $this->period = null;
    
    if ($slug)
    {
      $this->period = Doctrine::getTable('UllTimePeriod')->findOneBySlug($slug);
    }  
    
    if (!$this->period)
    {    
      $this->showError('No period for date ' . date('Y-m-d') . ' found. Please create a period on the administration page.');
    }
  }
  
  
  /**
   * Set global time compare validators for ullTimeReporting
   * 
   * @return none
   */
  protected function addGlobalValidators()
  {
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_work_at', 'end_work_at');
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_break1_at', 'end_break1_at');
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_break2_at', 'end_break2_at');
    $this->generator->getForm()->addGlobalCompareTimeValidator('begin_break3_at', 'end_break3_at');
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

  
  /**
   * Set generator global access depending on the locking status.
   * Normal users may only change data $lockingTimespanDays ago
   * Older entries are displayed read-only
   * 
   * @return string
   */
  protected function getLockingStatus()
  {
    if (UllUserTable::hasPermission('ull_time_ignore_locking'))
    {
      return 'w';
    }
    
    $this->lockingTimespanDays = sfConfig::get('app_ull_time_locking_timespan_days', 30);
    
    if ($this->doc->date > (date('Y-m-d', time() - $this->lockingTimespanDays * 24 * 60 * 60)))
    {
      return 'w';
    }
    
    $this->getUser()->setFlash(
      'message', 
      __('Read only access. Entries are locked after %days% days. Please contact your superior if you need to edit your data.', 
        array('%days%' => $this->lockingTimespanDays), 
        'ullTimeMessages'
      ),
      false
    );
    
    return 'r';
  } 
  
  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullFilterForm';
  }  
  
  
  /**
   * Apply custom modifications to the query
   *
   * This function builds a query selecting UllUsers for the phone book;
   * see inline comments for further details.
   */
  protected function modifyQueryForFilter()
  {
    $this->q->getDoctrineQuery()
      ->addSelect('SUM(x.duration_seconds) as duration_seconds_sum')
      ->addGroupBy('x.ull_project_id')
    ;
    
  }
}