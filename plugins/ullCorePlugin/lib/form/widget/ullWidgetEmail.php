<?php

class ullWidgetEmail extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('show_icon_only');
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('show_icon_only'))
    {
      return '<div class="email_icon">' .
        $this->renderContentTag('a', ull_image_tag('mail'), array('href' => 'mailto:' . $value)) .
        '</div>';
    }
    
    return $this->renderContentTag('a', $value, array('href' => 'mailto:' . $value));
  }
}
