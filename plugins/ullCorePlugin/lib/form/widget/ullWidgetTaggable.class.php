<?php

/**
 * Taggable widget
 * 
 * remark: some code is copied from http://www.symfony-project.org/plugins/sfDoctrineActAsTaggablePlugin
 *
 */
class ullWidgetTaggable extends sfWidgetFormInput
{
  var $script_options = array(
      'typeahead-url' => null,
      'tags-label' => null,
      'popular-tags' => null,
      'all-tags' => null,
      'commit-selector' => null,
      'commit-event' => null,
      'popular-tags-label' => null,
      'add-link-class' => null,
      'remove-link-class' => null,
      'selected_tag_markup' => null,
      'add-tag-label' => null
    );
    
  protected function configure($options = array(), $attributes = array())
  {
    foreach ($this->script_options as $key => $value)
    {
      $this->addOption($key, $value);
    }
    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  { 
    $render_options = array();
    
    foreach($this->script_options as $key => $val)
    {
      if ($this->options[$key])
      {
        $render_options[$key] = $this->options[$key];
      }
    }
    
    if (isset($this->options['default']))
    {
      $value = $this->options['default'];
    }
    
    $attributes['id'] = $this->generateId($name);
    $html = parent::render($name, $value, $attributes, $errors);
    $html .= "
<script type='text/javascript'>
  $(document).ready(
    function() 
    { 
      taggableWidget('#" . $attributes['id'] . "', " . json_encode(($render_options)) . "); 
    } 
  );
</script>";
    
    return $html;
  }
  
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return array(
      '/ullCorePlugin/js/jq/jquery-min.js', 
      '/ullCorePlugin/js/jq/jquery-ui-min.js',
      '/ullCorePlugin/js/taggable.js',
    );   
  }  
  
  
/**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */  
  public function getStylesheets()
  {
    return array(
      '/ullCorePlugin/css/jqui/jquery-ui.css' => 'all',
    );  
  }
  
}
