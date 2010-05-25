<?php

/**
 * This widget renders a static star-select ratings bar.
 * 
 * The 'add_random_identifier' option specifies whether
 * a randomly generated string should be added to the
 * name attribute (necessary when displaying multiple
 * bars on the same page)
 */
class ullWidgetRatingRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    //push this option into superclass?
    $this->addOption('add_random_identifier', true);
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return '<small>' . __('No ratings yet', null, 'ullCoreMessages') . '</small>';
    }
    
    $html = '';
    $roundedAvg = round($value, 1);
    $checkedStar = $roundedAvg / 0.5;
    
    $name = ($name) ? $name : 'avg_rating';
    $name = $this->getOption('add_random_identifier') ? $name . '_' . uniqid() : $name;
    $this->setAttribute('name', $name);

    $attributeArray = array(
      'class' => 'star {split:2}',
      'type' => 'radio',
      'disabled' => 'disabled'
    );
    
    for($i = 1; $i <= 10; $i++)
    {
      if ($i == $checkedStar)
      {
        $html .= $this->renderTag('input', $attributeArray + array('checked' => 'checked'));
      }
      else
      {
        $html .= $this->renderTag('input', $attributeArray);
      }
    }
    
    //this addition waits for the document to load
    //and sets the currently rendering rating stars
    //to read only - why we even need this, is a mystery :)
    //but without this, we experience some browser
    //reloading-strangeness
    
    $html .= <<<EOF
    <script type="text/javascript">
      $(function(){
        $("input[name='$name']").rating('readOnly', true);
      });
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
      '/ullCorePlugin/js/star-rating/jquery.rating.pack.js',
      '/ullCorePlugin/js/star-rating/jquery.MetaData.js',
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
      '/ullCorePlugin/css/star-rating/star-rating.css' => 'all'
    );  
  }
  
}