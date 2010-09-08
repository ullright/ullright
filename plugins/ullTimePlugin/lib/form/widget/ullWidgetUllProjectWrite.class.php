<?php 

/**
 * Special widget which offers a top 10 list of most used projects
 *    
 * @author klemens.ullmann-marx@ull.at
 */
class ullWidgetUllProjectWrite extends ullWidgetFormDoctrineSelect 
{
  
  public function __construct($options = array(), $attributes = array())
  {
    $q = new Doctrine_Query;
    $q->from('UllProject p, p.Translation t');
    $q->where('p.is_active = ?', true);
    $q->addWhere('t.lang = ?', substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
    $q->orderBy('t.name');
    
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
