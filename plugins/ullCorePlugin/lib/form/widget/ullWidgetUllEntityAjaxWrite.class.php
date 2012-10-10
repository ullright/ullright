<?php

/**
 * Checkbox read widget
 * 
 * Also supports ajax handling e.g. for list actions
 * By default the ullTableTool/updateSingleColumn action is used as ajax target
 * @author klemens
 *
 */
class ullWidgetUllEntityAjaxWrite extends ullWidget
{
  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('entity_classes');
    $this->addOption('hide_choices');
    $this->addOption('filter_users_by_group');
    // Not used, but necessary for compatibility with ullWidgetFormChoiceUllEntity
    //   to share inline editing support
    $this->addOption('choices');

    parent::configure($options, $attributes);
  }

  /**
   * Render code
   * 
   * @see plugins/ullCorePlugin/lib/form/widget/ullWidget::render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // Generate id
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }    
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    // Handle onchange submit
    $onchangeSubmit = false;
    if ('submit()' == $this->getAttribute('onchange'))
    {
      $this->setAttribute('onchange', null);
      $onchangeSubmit = true; 
    }
    
    // Handle input field size
    if (!$this->getAttribute('size'))
    {
      $this->setAttribute('size', 30);
    }
    
    // Check for permission
    // This is the permission from the action's checkPermission() method 
    $permission = 'none';
    if (sfContext::getInstance()->has('permission'))
    {
      $permission = sfContext::getInstance()->get('permission');
    }    
    
    // Load default
    $default = '';
    $entity = UllEntityTable::findById($value);
    if ($entity)
    {
      $default = (string) $entity;  
    }
    
    // Rendering
    $return = '';
    
    $return .= $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value));
    
    $return .= $this->renderTag('input', array(
      'type' => 'text', 
      'name' => $id . '_ajax', 
      'value' => $default,
      'class' => 'ull_widget_ull_entity_ajax_write_search',
    ));
    
    //$return .= '<div><dfn>' . __('Autocomplete. Begin to type your search term and select an entry from the appearing list', null, 'ullCoreMessages') . '</dfn></div>';
    
    
    $url = url_for('ullUser/ajaxAutoComplete?s_data=' . $this->generateUrlParams($permission));
    
    $return .= javascript_tag('
$(function() {

  $("#' . $id . '_ajax").autocomplete({
    source: "' . $url . '",
    minLength: 0,
    delay: 400,
    
    select: function( event, ui ) {
      /* Set the entity id in the original form field */
      $("#' . $id. '").val(ui.item.id);
      
      /* If onchange=submit() is supplied, submit the correct field */' .
      ($onchangeSubmit ? ('this.form.submit();') : '') . '        
    }    
  });

  // Support for inline editing: try to get a string value from the overlay result
  $("#' . $id . '").change(function () {
    if (window.overlayString != null) {
      $("#' . $id . '_ajax").val(window.overlayString);
    }
  });
  
  
  
});    
    ');
    
    return $return;
    
  }
  
  /**
   * Generate url params for encryption
   * 
   * @param string $permission
   */
  protected function generateUrlParams($permission)
  {  
    $optionNames = array(
      'entity_classes',
      'hide_choices',
      'filter_users_by_group'
    );

    $params = array();
    
    $params['permission'] = $permission;
    
    foreach($optionNames as $optionName)
    {
      if ($value = $this->getOption($optionName))
      {
        $params[$optionName] = $value;
      }
    }
    
    return serialize($params);
  }
  
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js', 
      '/ullCorePlugin/js/jq/jquery-ui-min.js',
    );
  }
  
  public function getStylesheets()
  {
    return array (
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
    );
  }
  
}
