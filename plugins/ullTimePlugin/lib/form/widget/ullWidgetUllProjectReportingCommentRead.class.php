<?php

/**
 * This widget renders the comment column of an UllProjectReporting record.
 * If the linked_id column of that record is set it returns an HTML link
 * to the edit page of the linked record. Otherwise, the comment column is
 * rendered as text.
 * 
 */
class ullWidgetUllProjectReportingCommentRead extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $projectReportingId = $value['id'];
      
      $projectReporting = Doctrine::getTable('UllProjectReporting')->findOneById($projectReportingId);
    
      if ($projectReporting !== false && $projectReporting['linked_id'] !== null)
      {
        $linkedModel = Doctrine::getTable($projectReporting['linked_model'])->findOneById($projectReporting['linked_id']);
  
        if ($linkedModel === false)
        {
          return __('Linked record is not available', null, 'ullTimeMessages');
        }
        
        return '<a href="' . url_for($linkedModel->getEditUri()) . '">' .
          esc_entities((string) $linkedModel) . '</a>';
      }      
    }

    return parent::render($name, $value, $attributes, $errors);
  }
}
