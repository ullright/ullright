<?php

/**
 * This widget renders an image-based checkbox with manual links
 * to a configurable flag handling action.
 * If JS is enabled a small script will replace this control
 * with a 'real' checkbox, which is AJAX-enhanced to provide
 * flag toggling without page reloads.
 * 
 * THIS IS NOW DEPRECATED and replaced by ullWidgetCheckbox
 * 
 * @deprecated
 */
class ullWidgetAjaxCheckbox extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('urlToFlagHandler'); //usually url_for('ullTableTool/setUserFlag')
    $this->addOption('model'); //e.g. 'UllClimbingRoute'
    $this->addOption('flag'); //e.g. 'cleared'
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //We need the injected identifier
    if (is_array($value))
    {
      $flagObjectId = $value['id'];
      $value = $value['value'];
    }
    else
    {
      throw new InvalidArgumentException('$value must be an array, use id injection');
    }

    //create various ids
    $name = $name . '_' . uniqid();
    $this->setAttribute('name', $name);
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    $idOfIndicator = $id . '_indicator';
    
    //is flag set to true or false?
    $image = ($value) ? 'checkbox_checked' : 'checkbox_unchecked';

    //read configuration (we should do some safety checking here)
    $urlToFlagHandler = $this->getOption('urlToFlagHandler');
    $flagName = $this->getOption('flag');
    $modelName = $this->getOption('model');
    
    //build complete URL to flag handler
    $flagHandlingUrl = $urlToFlagHandler .
      '?table=' . $modelName . '&id=' . $flagObjectId .
      '&flag=' . $flagName . '&value=' . (($value) ? 'false' : 'true');
    
    //set the id of this link so that we can replace it later on
    $html = ull_link_to(ull_image_tag($image, array('class' => $image), 9, 9, 'ullCore'),
      $flagHandlingUrl, array('id' => $id));
    

    //create the 'real' checkbox replacement HTML ...
    $widget = new sfWidgetFormInputCheckbox(array(), $attributes);
    $checkboxHTML = $widget->render($name, ($value) ? true : false);
    //... and add visual indicator code
    $checkboxHTML .= image_tag('/ullCoreThemeNGPlugin/images/indicator.gif',
      array('id' => $idOfIndicator, 'style' => 'display: none; vertical-align: middle;'));

    //append JS which will replace the faked image-checkbox
    //with the real, AJAX-enhanced one
    $html .= <<<EOF
<script type="text/javascript">
//<![CDATA[
$(function() {
  $("#$id").replaceWith('$checkboxHTML');  

  $("#$id").click(function()
  { 
    flagToggle(this, $("#$idOfIndicator"), '$urlToFlagHandler', '$modelName', $flagObjectId, '$flagName');
    return false;
  });
});
//]]>
</script>
EOF;

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
    );
  }

}
