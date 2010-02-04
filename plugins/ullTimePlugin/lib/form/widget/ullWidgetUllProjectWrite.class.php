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
    $return = '<div style="float: left;">';
    
    $return .= parent::render($name, $value, $attributes, $errors);
    
    $return .= '</div>';
    $return .= '<div style="float: left;">';
        
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $projects = UllProjectReportingTable::findLatestTopProjects();
    
    $return .= '<ul style="margin-top: 0; margin-bottom: 0; margin-left: 2em;">';
    
    foreach ($projects as $projectId => $projectName)
    {
      $return .= '  <li style="margin-bottom: .2em;">';
      $return .= link_to_function($projectName, 'getElementById("' . $id . '").value=' . $projectId);
      $return .= "  </li>\n";  
    }
    $return .= "</ul>\n";
    $return .= '</div>';
    
    return $return;
  }  
  
}
