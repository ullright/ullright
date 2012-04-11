<?php 

/**
 * Special widget which offers a top 10 list of most used projects
 *    
 * @author klemens.ullmann-marx@ull.at
 */
class ullWidgetUllProjectWrite extends ullWidgetFormDoctrineChoice 
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $userId = UllUserTable::findLoggedInUserId();
    
    $q = new ullDoctrineQuery;
    $q
      ->from('UllProject p, p.Translation t')
      ->leftJoin('p.UllProjectManager pm WITH pm.ull_user_id = ?', $userId)
      
      ->where('p.is_active = ?', true)
      ->addWhere('t.lang = ?', substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2))
      
      
      ->addWhere('p.is_visible_only_for_project_manager = ?', false)
      ->openParenthesisBeforeLastPart()
      
      ->orWhere('p.is_visible_only_for_project_manager = ?', true)
      ->openParenthesisBeforeLastPart()
        ->andWhere('pm.ull_user_id = ?', $userId)
      ->closeParenthesis()
      
      ->closeParenthesis()
      
      ->orderBy('t.name')
    ;
    
//    var_dump($q->getSqlQuery());
//    var_dump($q->getParams());
//    
//    var_dump($q->execute()->toArray());
//    
//    die('ullWidgetUllProjectWrite');
    
    $options['query'] = $q;
    
    parent::__construct($options, $attributes);
  }
  
  /**
   * @see plugins/ullCorePlugin/lib/vendor/symfony/lib/widget/sfWidgetFormSelect#render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '<div id="ull_time_project_quicklink">';
    $return .= parent::render($name, $value, $attributes, $errors);
    $return .= '<div>';
    
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');

    
    
    // top projects
    $return .= '<div id="ull_time_project_quicklink_top" class="ull_time_project_quicklink_list">';
    $return .= '<h4>' . __('My projects', null, 'ullTimeMessages') . '</h4>'; 
    $projects = UllProjectReportingTable::findLatestTopProjects();
    
    $return .= '<ul>';
    
    foreach ($projects as $projectId => $projectName)
    {
      $return .= '  <li>';
      $return .= link_to_function($projectName, 'getElementById("' . $id . '").value=' . $projectId);
      $return .= "  </li>\n";  
    }
    $return .= "</ul>\n";
    $return .= "</div>";
    
    
    // routine projects
    $return .= '<div id="ull_time_project_quicklink_routine" class="ull_time_project_quicklink_list">';
    $return .= '<h4>' . __('Routine projects', null, 'ullTimeMessages') . '</h4>';    
    
    $projects = UllProjectReportingTable::findLatestRoutineProjects();
    
    $return .= '<ul>';
    
    foreach ($projects as $projectId => $projectName)
    {
      $return .= '  <li>';
      $return .= link_to_function($projectName, 'getElementById("' . $id . '").value=' . $projectId);
      $return .= "  </li>\n";  
    }
    $return .= "</ul>\n";
    $return .= "</div>";
    
    return $return;
  }  
  
}
