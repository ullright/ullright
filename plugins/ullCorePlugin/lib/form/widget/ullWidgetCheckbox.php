<?php

/**
 * Checkbox read widget
 * 
 * Also supports ajax handling e.g. for list actions
 * By default the ullTableTool/updateSingleColumn action is used as ajax target
 * @author klemens
 *
 */
class ullWidgetCheckbox extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('enable_ajax_update'); 
    $this->addOption('ajax_url'); //e.g. 'ullTableTool/updateSingleColumn'
    $this->addOption('ajax_model'); //e.g. 'UllUser'
    $this->addOption('ajax_column'); //e.g. 'is_superior'
    
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    if (is_array($value))
    {
      $objectId = $value['id'];
      $value = $value['value'];
      
      $this->setAttribute('name', $name . '_' . $objectId);
    }
    else
    {
      if ($this->getOption('enable_ajax_update'))
      {
        throw new InvalidArgumentException('$value must be an array, use id injection');
      }
    }
    
//    var_dump($value);
//    
    //fix value for list action filter
    if ('unchecked' === $value)
    {
      $value = false;
    } 
    
    //create various ids
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    $image = ($value) ? 'checkbox_checked' : 'checkbox_unchecked';
    
    $return .= ull_image_tag($image, array('class' => $image, 'id' => $id), 9, 9, 'ullCore');
    
    if ($this->getOption('enable_ajax_update'))
    {
      //create the 'real' checkbox replacement HTML ...
      $checkboxHTML = '';
      
      $widget = new sfWidgetFormInputCheckbox(array());
      $checkboxHTML .= $widget->render($name, ($value) ? true : false, $this->getAttributes() );
      
      //... and add visual indicator code
      $indicatorId = $id . '_indicator';
      $checkboxHTML .= image_tag('/ullCoreThemeNGPlugin/images/indicator.gif',
        array('id' => $indicatorId, 'style' => 'display: none; vertical-align: middle;')
      );

      //build ajax URL to flag handler
      $ajaxUrl = url_for( 
        $this->getOption('ajax_url') .
        '?table=' . $this->getOption('ajax_model') .
        '&column=' . $this->getOption('ajax_column') .
        '&id=' . $objectId 
      );
  
      //append JS which will replace the faked image-checkbox
      //with the real, AJAX-enhanced one
      $return .= <<<EOF
<script type="text/javascript">
//<![CDATA[
$(function() {
  $("#$id").replaceWith('$checkboxHTML');  

  $("#$id").click(function()
  { 
    ajax_update(this, '$indicatorId', '$ajaxUrl');
    return false;
  });
});
//]]>
</script>
EOF;

         
      
    }
    
    return $return; 
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
      '/ullCorePlugin/js/ajax_update.js'
    );
  }
  
}
