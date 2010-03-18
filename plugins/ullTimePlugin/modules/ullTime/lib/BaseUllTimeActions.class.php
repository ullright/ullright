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
    
    if (UllUserTable::hasPermission('ull_time_enter_future_periods'))
    {
      $futurePeriods = UllTimePeriodTable::findOneYearInFuture();
      if (count($futurePeriods))
      {
        $this->future_periods = $futurePeriods;
      }
    }
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
    
    $this->prepareListData();
    
    $this->breadcrumbForList();
  }
  
  
  /**
   * Execute reporting for project efforts
   * @param $request
   * @return unknown_type
   */
  public function executeReportProject(sfRequest $request)
  {
//    $this->checkPermission('ull_time_report');
    
    $this->breadcrumbForReportProject();
    
    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }
    
    // Must be a string as request params come as strings
    $request->setParameter('paging', 'false');
    
    $this->report = $request->getParameter('report');
    
    if (!$request->getParameter('order'))
    {
      switch ($this->report)
      {
        case 'details':
          $request->setParameter('order', 'date');
          break;
        
        default:
          $request->setParameter('order', 'UllProject->name');
      }
      
    }
    
    
    
    $this->generator = new ullTimeReportGenerator($this->report, $request->getParameter('filter'));
    $this->generator->setCalculateSums(true);
    
    $rows = $this->getFilterFromRequest();
    
    $this->generator->buildForm($rows);
    
//    var_dump($this->generator->getForm()->debug());
//    var_dump($this->generator->getColumnsConfig());
    
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
    
    //ull_url_for() uses reqpassing, we have to remove the form fields
    //here to fix #948. Why is this workaround necessary? Do we
    //even need reqpassing here?
    $this->form_uri = ull_url_for(array('fields' => null));
    
    $this->getDocFromRequestOrCreate();
    
    $this->generator = new ullTableToolGenerator('UllTimeReporting', $this->getLockingStatus());
    $this->generator->buildForm($this->doc);
    $this->addGlobalValidators();
    
    //$this->sum_time = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($request->getParameter('date'), $this->user_id);
    //$this->is_today = ($request->getParameter('date') == date("Y-m-d",time()));
    $this->break_1_duration = $this->doc->getBreakDuration(1);
    $this->break_2_duration = $this->doc->getBreakDuration(2);
    $this->break_3_duration = $this->doc->getBreakDuration(3);
    $this->up_to_now = strtotime(date("H:i",time())) - strtotime($this->doc->begin_work_at) - $this->doc->total_break_seconds;
    
    $sumTime = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($request->getParameter('date'), $this->user_id);
    if (($request->getParameter('date') != date("Y-m-d",time())) || !$this->doc->begin_work_at)
    {
      $this->up_to_now = 0;
    }
    
    if($sumTime > 0)
    {
      $this->up_to_now = $sumTime;
    }
    
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
   * Execute edit project effort action
   * 
   */
  public function executeEditProject(sfRequest $request) 
  {
    $this->checkPermission('ull_time_edit_project');
    
    //See executeEdit above why we do this (#948)
    $this->form_uri = ull_url_for(array('fields' => null));
    
    $this->getProjectReportingFromRequestOrCreate();
    
    $this->list_generator = new ullTableToolGenerator('UllProjectReporting', 'r', 'list');
    $this->list_generator->setCalculateSums(true);
    $this->list_generator->buildForm($this->docs);
    
    $this->sum_time = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($request->getParameter('date'), $this->user_id);
    $this->diff_time = $this->sum_time - UllProjectReportingTable::findSumByDateAndUserId($request->getParameter('date'), $this->user_id);
    
    if ($this->sum_time && !$this->doc->getDurationSeconds() && $this->diff_time > 0)
    {
      $this->doc->setDurationSeconds($this->diff_time);
    }
    
    $this->edit_generator = new ullTableToolGenerator('UllProjectReporting', $this->getLockingStatus());
    
    $this->disableCommentForLinkedProjectEfforts();
    
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
   * Disable the "comment" field for linked project efforts
   * 
   * @return none
   */
  protected function disableCommentForLinkedProjectEfforts()
  {
    if ($this->doc->linked_model)
    {
      $this->edit_generator->getColumnsConfig()->offsetGet('comment')->disable();
    }    
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
    $breadcrumbTree = new ullTimeBreadcrumbTree();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->addDefaultListEntry();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }  

  /**
   * Create breadcrumbs for reportProject action
   * 
   */
  protected function breadcrumbForReportProject() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->add(__('Reporting', null, 'ullTimeMessages'));
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }   
  
  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->setEditFlag(true);
    $breadcrumbTree->addDefaultListEntry();

    if ($this->doc->exists()) 
    {
      $breadcrumbTree->add(__('Edit time report', null, 'ullTimeMessages'));
    } 
    else 
    {
      $breadcrumbTree->add(__('Create time report', null, 'ullTimeMessages'));
    }

    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  } 

  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEditProject() 
  {
    $breadcrumbTree = new ullTimeBreadcrumbTree;
    $breadcrumbTree->setEditFlag(true);
    $breadcrumbTree->addDefaultListEntry();

    if ($this->doc->exists()) 
    {
      $breadcrumbTree->add(__('Edit project effort', null, 'ullTimeMessages'));
    } 
    else 
    {
      $breadcrumbTree->add(__('Create project effort', null, 'ullTimeMessages'));
    } 
    
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
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
    return 'ullTimeProjectFilterForm';
  }  
  
  
  /**
   * Apply custom modifications to the query
   *
   * This function builds a query selecting UllUsers for the phone book;
   * see inline comments for further details.
   */
  protected function modifyQueryForFilter()
  {
    
    //filter per user
    if ($ullUserId = $this->filter_form->getValue('ull_user_id'))
    {
      $this->q->addWhere('ull_user_id = ?', $ullUserId);
      $this->user = Doctrine::getTable('UllEntity')->findOneById($ullUserId);
      
      $this->ull_filter->add('filter[ull_user_id]', __('User', null, 'common') . ': ' . $this->user);
      
      // both do not work because the form is already bound
//      $this->filter_form->setDefault('ull_user_id', null);
//      $this->filter_form->setValue('ull_user_id', null);
    }
    else
    {
      $this->user = null;
    }

    //filter per project
    if ($projectId = $this->filter_form->getValue('ull_project_id'))
    {
      $this->q->addWhere('ull_project_id = ?', $projectId);
      $this->project = Doctrine::getTable('UllProject')->findOneById($projectId);
      
      $this->ull_filter->add('filter[ull_project_id]', __('Project', null, 'ullTimeMessages') . ': ' . $this->project);
      
      // both do not work
//      $this->filter_form->setDefault('ull_user_id', null);
//      $this->filter_form->setValue('ull_user_id', null);
    }
    else
    {
      $this->project = null;
    }     

    // filter per date
    
    // filter from date
    $dateWidget = new ullWidgetDateRead();
    if ($fromDate = $this->filter_form->getValue('from_date'))
    {
      $this->q->addWhere('date >= ?', $fromDate);
      $this->ull_filter->add('filter[from_date]', __('Begindate', null, 'common') . ': ' . $dateWidget->render(null, $fromDate));
    }
    
    // filter to date
    if ($toDate = $this->filter_form->getValue('to_date'))
    {
      $this->q->addWhere('date <= ?', $toDate);
      $this->ull_filter->add('filter[to_date]', __('Enddate', null, 'common') . ': ' . $dateWidget->render(null, $toDate));
    }
    
    if ($this->report != 'details')
    {
      // Add artificial sum field
      $this->q->getDoctrineQuery()
        ->addSelect('SUM(x.duration_seconds) as duration_seconds_sum')
      ;
    }
    
    switch ($this->report)
    {
      case 'by_project':      
        $this->q->getDoctrineQuery()
          ->addGroupBy('x.ull_project_id')
        ;
        break;
        
      case 'by_user':
        $this->q->getDoctrineQuery()
          ->addGroupBy('x.ull_user_id')
        ;
        break;
    }    

    
    //access check
    $this->q = UllProjectReportingTable::queryAccess($this->q);
  }
  
  
  /**
   * Prepare data for the list (period overview)
   * 
   * @return none
   */
  protected function prepareListData()
  {
    //$rawDates is sorted by date, ascending, but we need descending
    $rawDates = $this->period->getDateList();
    krsort($rawDates);
    
    $periodTable = array();
    $calendarWeek = null;
    $week = null;
    $this->totals = array('time' => 0, 'project' => 0, 'delta' => 0);
    
    foreach($rawDates as $date => $day)
    {
      if ($date <= date('Y-m-d') || UllUserTable::hasPermission('ull_time_enter_future_periods'))
      {
        //check if we have a calendar week switch
        if ($calendarWeek !== $day['calendarWeek'])
        {
          //if this is not the first switch of a month,
          //save the created week in the period table
          if ($week != null)
          {
            $periodTable[$calendarWeek] = $week;
          }
          
          $calendarWeek = $day['calendarWeek'];

          //create an empty week
          $week = array();
          $week['sum_project'] = 0;
          $week['sum_time'] = 0;
          $week['sum_delta'] = 0;
          $week['future'] = true;
        }
        
        //update the week with its days and add sums
        
        $week['dates'][$date] = $day;

        $sumTime = UllTimeReportingTable::findTotalWorkSecondsByDateAndUserId($date, $this->user_id);
        $week['dates'][$date]['sum_time'] = $sumTime; 
        $week['sum_time'] += $sumTime;
        $this->totals['time'] += $sumTime;
        
        $sumProject = UllProjectReportingTable::findSumByDateAndUserId($date, $this->user_id);  
        $week['dates'][$date]['sum_project'] = $sumProject;
        $week['sum_project'] += $sumProject;
        $this->totals['project'] += $sumProject;
        
        $sumDelta = $sumTime - $sumProject;
        $week['dates'][$date]['sum_delta'] = $sumDelta;
        $week['sum_delta'] += $sumDelta;
        $this->totals['delta'] += $sumDelta;
        
        // mark future dates
        if ($date > date('Y-m-d'))
        {
          $week['dates'][$date]['future'] = true;
        }
        else
        {
          $week['dates'][$date]['future'] = false;
          $week['future'] = false;
        }
      }
    }
    
    //Let us not forget the last week
    $periodTable[$calendarWeek] = $week;
    
    $this->period_table = $periodTable;    
  }
}