<?php

/**
 * ullMailLog actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllMailLogActions extends BaseUllGeneratorActions
{
  
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();

    //add module stylsheet for all actions but show
    if ($this->getActionName() !== 'show')
    {
      $path =  '/ullMailTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
      $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
    }   
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->redirect('ullMailLog/list');
  }
  
  /**
   * Execute list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_log_list');
    
    $this->named_queries = new ullNamedQueriesUllMailLog();
    
    parent::executeList($request);
    
    $this->renderChart();
  }
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    $ullNewsletterEditionId = $this->getRequest()->getParameter('ull_newsletter_edition_id');
    
    if (!$ullNewsletterEditionId)
    {
      $this->redirect404();
    }    
    
    $ullNewsletterEdition = Doctrine::getTable('UllNewsletterEdition')
      ->findOneById($ullNewsletterEditionId);
      
    if (!$ullNewsletterEdition)
    {
      $this->redirect404();
    }
    
    $this->q->addWhere('ull_newsletter_edition_id = ?', $ullNewsletterEditionId);
    
    $this->setVar('ull_newsletter_edition', $ullNewsletterEdition, true);
  }   
  
  /**
   * Delete is not allowed here
   */
  public function executeDelete(sfRequest $request)
  {
    $this->redirect404();
  }
  
  
  /**
   * Execute edit action
   * 
   * @param sfWebRequest $request
   */  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_log_edit');
    
    parent::executeEdit($request);
  }
  
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullMailLogGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getEditGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullMailLogGenerator('r');
  }

  protected function renderChart()
  {
    /**
     * Minimal example
     *
     * Taken from the pChart example library
     *
     * @link http://pchart.sourceforge.net/screenshots.php
     * @link http://code.google.com/p/mtchart/
     */
    
    $this->dir = sfConfig::get('sf_upload_dir'). '/charts/ullMailLog';
    
    if (!file_exists($this->dir))
    {
      mkdir($this->dir, '0777', true);
    }
    
    $data = array(
      'hour' => array(),
      'per_hour' => array(),
//      'per_month_closed' => array(),
      'total' => array(),
//      'total_closed' => array(),
    );
    
    $q = new ullQuery('UllMailLoggedMessage');
    $q
      ->addSelect('COUNT(*) as sum, SUBSTR(created_at, 1, 13) as hour')
      ->addWhere('ull_newsletter_edition_id = ?', $this->ull_newsletter_edition->id)
      ->addWhere('first_read_at IS NOT NULL')
      ->addGroupBy('hour') 
    ;    
    
    $result = $q->execute(array(), Doctrine::HYDRATE_SCALAR);
    
    foreach ($result as $value)
    {
      $data['hour'][] = $value['x_hour'];
      $data['per_hour'][] = $value['x_sum'];
      $data['total'][] = end($data['total']) + $value['x_sum'];
    }
    
//        $q->addWhere('UllFlowAction->slug = ?', 'close');
//    
//    $result = $q->execute(array(), Doctrine::HYDRATE_SCALAR);
//    
//    foreach ($result as $value)
//    {
//      $data['per_month_closed'][] = $value['x_sum'];
//      $data['total_closed'][] = end($data['total_closed']) + $value['x_sum'];
//    }    
    
//    var_dump($data);die;

    
    // Dataset definition
    $Test = new mtChart(950,350);
    $Test->enableCaching(null, $this->dir . '/');
//    $Test->addPoint(array(1,4,3,2,3,3,2,1,0,7,4,3,2,3,3,5,1,0,7));
    $Test->addPoint($data['per_hour'], 'per_hour');
//    $Test->addPoint($data['per_month_closed'], 'per_month_closed');
    $Test->addPoint($data['total'], 'total');
//    $Test->addPoint($data['total_closed'], 'total_closed');
    $Test->addPoint($data['hour'], 'legend');
    
    $Test->AddSerie('per_hour');
//    $Test->AddSerie('per_month_closed');

    $Test->SetAbsciseLabelSerie("legend"); 
    $Test->setSerieName("Readings per hour", "per_hour");
//    $Test->setSerieName("Tickets per month closed", "per_month_closed");
//    $Test->setSerieName("Tickets total","total");
//    $Test->setSerieName("Tickets total closed","total_closed");
    $Test->setYAxisName('Num of readings');
    
    
    if ($Test->isInCache())
    {
      return;
    }
    
    
//    $cached = $Test->getFromCache(false);
    
        
    
    // Initialise the graph
    $Test->setFontProperties('DejaVuSansCondensed',10);
    $Test->setGraphArea(55,30,850,300);
//    $Test->drawGraphArea(252,252,252,TRUE);
    // 6th param = angle of x-axis labels
    $Test->setInterval(3);
    $Test->setFixedScale(0, 100);
    $Test->drawScale(SCALE_NORMAL,150,150,150,TRUE,45,1);
    $Test->drawGrid(4,true,230,230,230,70);
    
//    $Test->setFontProperties("DejaVuSansCondensed",6);  
//    $Test->drawTreshold(0,143,55,72,TRUE,TRUE);  
    
    // Draw the line graph
    $Test->drawLineGraph();
    $Test->drawPlotGraph(3,2,255,255,255, true);
    
    
    
    // totals
//    $Test->clearScale();
//    $Test->removeSerie('per_month');
//    $Test->removeSerie('per_month_closed');
//    $Test->AddSerie('total');
//    $Test->AddSerie('total_closed');
//    $Test->setYAxisName('Totals');
    
    
//    
//    $Test->setFixedScale(0, 750);
//    $Test->drawRightScale(SCALE_NORMAL,150,150,150,TRUE,45,1);
////    $Test->drawGrid(4,TRUE,230,230,230,70);
//    
//    $Test->drawLineGraph();
//    $Test->drawPlotGraph(3,2,255,255,255, true);    
    
    
    
    
    // Finish the graph
    $Test->setFontProperties('DejaVuSansCondensed',10);
    $Test->drawLegend(45,35,255,255,255);
    $Test->setFontProperties('DejaVuSansCondensed',10);
//    $Test->drawTitle(50,22,"Tickets",50,50,50);

    
    
    $Test->render($this->dir . '/test.png');

  }    
  
}