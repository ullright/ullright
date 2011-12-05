<?php 

/**

 *    
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullWidgetUllFlowAppLinkWrite extends sfWidgetFormInputHidden
{
  
  /**
   * Constructor.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addRequiredOption('app');
  }  


  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    $app = Doctrine::getTable('UllFlowApp')->findOneBySlug($this->getOption('app'));
    
    // create
    if (!$value || in_array($value, array('create_save', 'create_send')))
    {
//      $return .= link_to(
//        __('Create new "%app%"', array('%app%' => $app['doc_label']), 'ullFlowMessages'),
//        'ullFlow/create?app=' . $app['slug']
//      );
      
      $choices = array(
//      'create_save' => __('Create', null, 'common') . ' ' . $app->label,
      'create_send' => __('Create and send', null, 'common') . ' ' . $app->label,
        '' => __('Do nothing', null, 'ullFlowMessages')
      
//        'create_send' => 'Create and send workflow'
      );
      
      $widget = new sfWidgetFormSelect(array('choices' => $choices));
      
      $return .= $widget->render($name, $value);
      
    }   
    // edit
    else
    {
      $return .= parent::render($name, $value, $attributes, $errors);
      
      $doc = Doctrine::getTable('UllFlowDoc')->findOneById($value);
      
      if (!$doc)
      {
        return __('Not found, possibly deleted', null, 'ullFlowMessages') . ' (Id:' . $value . ')';
      }
      
//      $return .= __('Status', null, 'ullFlowMessages') . ': ' . $doc['UllFlowAction']['label'];
//      $return .= ' ' . __('by', null, 'ullFlowMessages') . ' ' . $doc['Updator'];
//      $return .= ' ' . __('on', null, 'ullFlowMessages') . ' ' . ull_format_date($doc['updated_at']);
      
      $return .= __('Status', null, 'ullFlowMessages') . ': '; 
      $return .= __('In step', null, 'ullFlowMessages') .  ' "' . $doc['UllFlowStep']['label'] . '"';
      $return .= ', ' . strtolower($doc['UllFlowAction']['label']);
      
      if ($doc->UllFlowAction->is_show_assigned_to)
      {
        $return .= ' ' . $doc['UllEntity'];
      }
      $return .= ' ' . __('on', null, 'ullFlowMessages') . ' ' . ull_format_date($doc['updated_at']);      
      
      $return .= ' &nbsp; ' . link_to(
        __('Details', null, 'ullFlowMessages'),
        'ullFlow/edit?doc=' . $doc['id'],
        array('target' => '_blank', 'class' => 'link_new_window')
      );
    }
    
    return $return;
  
  }
  
}
