<?php

/**
 * Write percentage widget
 * 
 * @author klemens.ullmann@ull.at
 *
 */
class ullWidgetPercentageWrite extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('min', 0);
    $this->addOption('max', 100);
    $this->addOption('step', 1);
    $this->addOption('orientation', 'horizontal');

    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (!$this->getAttribute('name'))
    {
      $this->setAttribute('name', $name);
    }
    $this->setAttributes($this->fixFormId($this->getAttributes()));
    $id = $this->getAttribute('id');
    
    if ($value === null)
    {
      $value = 0;
    }
    
    $attributes['size'] = 2;
    $attributes['style'] = 'float: left;';
    
    $return = '
    <script type="text/javascript">
      $(function() {
        $("#' . $id . '_slider").slider({
          value: ' . $value . ',
          min: ' . $this->getOption('min') . ',
          max: ' . $this->getOption('max') . ',
          step: ' . $this->getOption('step') . ',
          orientation: "' . $this->getOption('orientation') . '",
          slide: function(event, ui) {
            $("#' . $id . '").val(ui.value);
          }
        });
        $("#' . $id . '").val($("#' . $id . '_slider").slider("value"));
      });
    </script>';

    $return .= '<div id="'. $id . '_slider" style="width: 10em; float: left; margin-top: .4em; margin-right: 1em;"></div>';
    
    $return .= $this->renderTag('input', array_merge(array('type' => 'text', 'name' => $name, 'value' => $value), $attributes));

    $return .= '&nbsp;<span style="line-height: 1.7em;">%</span>';
    
    return $return;
  }
}
