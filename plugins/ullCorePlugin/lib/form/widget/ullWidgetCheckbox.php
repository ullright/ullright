<?php

class ullWidgetCheckbox extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('use_ajax');
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $ajaxCallUrl = $this->getOption('use_ajax');

    //classic mode ...
    if (!$ajaxCallUrl)
    {
      if (is_array($value))
      {
        $value = $value['value'];
      }

      $image = 'checkbox_unchecked';

      if ($value)
      {
        $image = 'checkbox_checked';
      }

      return ull_image_tag($image, array('class' => $image), 9, 9, 'ullCore');
    }

    //... ajax mode!
    else
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
      
      $name = $name . '_' . uniqid();
      $this->setAttribute('name', $name);
      $this->setAttributes($this->fixFormId($this->getAttributes()));
      $id = $this->getAttribute('id');
      $idOfIndicator = $id . '_indicator';
      
      $widget = new sfWidgetFormInputCheckbox(array(), $attributes);
      $html = $widget->render($name, $value? true : false);
      $html .= image_tag('/ullCoreThemeNGPlugin/images/indicator.gif',
        array('id' => $idOfIndicator, 'style' => 'display: none; vertical-align: middle;'));
      
      $html .= <<<EOF
<script type="text/javascript">
$(function() {
  $("#$id").click(function()
  {
    flagToggle(this, $("#$idOfIndicator"), urlToFlagHandler, model, $flagObjectId, flag);
    return false;
  });
});
</script>
EOF;
        
      return $html;
    }
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
